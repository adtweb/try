<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleMassUpdateRequest;
use App\Http\Requests\ScheduleStoreRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;
use Src\Domains\Conferences\Models\Thesis;
use Src\Domains\Schedule\Jobs\SendScheduleChangeNotification;
use Src\Domains\Schedule\Models\Schedule;
use Src\Domains\Schedule\Models\ScheduleItem;

class ScheduleController extends Controller
{
    public function index(Conference $conference)
    {
        $conference->load([
            'schedules' => function ($query) {
                $query->with([
                    'scheduleItems' => fn ($query) => $query
                        ->orderBy('time_start')
                        ->orderBy('position')
                        ->with([
                            'thesis' => fn ($q) => $q->select(['solicited_talk', 'id', 'reporter', 'authors', 'thesis_id', 'title', 'report_form']),
                            'scheduleItemTags' => fn ($q) => $q->select(['title_ru', 'title_en', 'color', 'schedule_item_id', 'id']),
                        ]),
                ]);
            },
            'sections',
        ]);

        $unassignedTheses = [];

        foreach ($conference->sections as $section) {
            $thesesCount = Thesis::query()
                ->where('section_id', $section->id)
                ->whereDoesntHave('scheduleItem')
                ->count();

            $unassignedTheses[$section->id] = $thesesCount;
        }

        if (auth()->id() === $conference->user_id) {
            $moderableSectionsIds = $conference->sections->pluck('id')->all();
        } else {
            $moderableSectionsIds = auth()->user()->moderatedSections->pluck('id')->all();
        }

        return view('my.events.personal.schedules', compact('conference', 'moderableSectionsIds', 'unassignedTheses'));
    }

    public function store(Conference $conference, ScheduleStoreRequest $request): JsonResponse
    {
        $time = explode(':', $request->start_time);
        $startTime = Carbon::parse($request->date)->timezone('Europe/Moscow')->hour($time[0])->minute($time[1]);
        $time = explode(':', $request->end_time);
        $endTime = Carbon::parse($request->date)->timezone('Europe/Moscow')->hour($time[0])->minute($time[1]);

        $schedule = Schedule::create([
            'conference_id' => $conference->id,
            'date' => Carbon::parse($request->date)->timezone('Europe/Moscow'),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        $schedule->load('scheduleItems');

        return response()->json($schedule);
    }

    public function edit(Conference $conference, Schedule $schedule, Section $section)
    {
        $schedule->load([
            'scheduleItems' => fn ($query) => $query
                ->where('section_id', $section->id)
                ->orderBy('time_start')
                ->orderBy('position')
                ->with([
                    'thesis' => fn ($q) => $q->select(['solicited_talk', 'id', 'thesis_id', 'reporter', 'authors', 'report_form', 'title']),
                    'scheduleItemTags' => fn ($q) => $q->select(['title_ru', 'title_en', 'color', 'schedule_item_id', 'id']),
                ]),
        ]);

        $conference->load([
            'theses' => fn ($q) => $q->select(['solicited_talk', 'theses.id', 'thesis_id', 'reporter', 'authors', 'section_id', 'title', 'report_form']),
            'schedules' => fn ($q) => $q->select(['id', 'conference_id']),
        ]);
        $theses = $conference->theses;

        $assignedThesesIds = ScheduleItem::query()
            ->whereIn('schedule_id', $conference->schedules->pluck('id')->all())
            ->where('section_id', $section->id)
            ->get(['thesis_id'])
            ->pluck('thesis_id')
            ->toArray();

        $unassignedTheses = $theses
            ->where('section_id', $section->id)
            ->filter(fn ($el) => ! in_array($el->id, $assignedThesesIds))
            ->values()
            ->all();

        return view('my.events.personal.schedule', compact('conference', 'schedule', 'unassignedTheses', 'section'));
    }

    public function sendChanges(string $conferenceSlug, int $scheduleId, Section $section)
    {
        ScheduleItem::where('section_id', $section->id)
            ->where('schedule_id', $scheduleId)
            ->whereNotNull('thesis_id')
            ->select(['id', 'thesis_id'])
            ->chunk(30, function (Collection $items) {
                $theses = Thesis::whereIn('id', $items->pluck('thesis_id')->all())
                    ->get(['id', 'participation_id'])
                    ->groupBy('participation_id');

                foreach ($theses as $participationId => $thesesCollection) {
                    dispatch(new SendScheduleChangeNotification($thesesCollection, $participationId));
                }
            });
    }

    public function massUpdate(ScheduleMassUpdateRequest $request): JsonResponse
    {
        $existingItems = ScheduleItem::where('section_id', $request->section_id)
            ->where('schedule_id', $request->schedule_id)
            ->get(['id']);

        $schedule = Schedule::find($request->schedule_id);
        $lastTime = $schedule->date;
        $scheduleItems = new Collection;

        foreach ($request->items as $key => $item) {
            if ($key === 0) {
                $time = explode(' ', $item['start']);
                $inputStart = Carbon::parse($schedule->date)->hour($time[0])->minute($time[1]);
                $start = $inputStart;
            } else {
                $start = $lastTime;
            }

            $end = $start->clone()->addMinutes($item['duration']);

            $lastTime = $end;

            $thesisId = isset($item['thesis_id']) ? $item['thesis_id'] : null;

            if (isset($item['id'])) {
                $scheduleItem = ScheduleItem::find($item['id']);
                $scheduleItem->update([
                    'title' => $item['title'],
                    'time_start' => $start,
                    'time_end' => $end,
                    'position' => $key,
                ]);

                $existingItems = $existingItems->reject(fn ($el) => $el->id === (int) $item['id']);
            } else {
                $scheduleItem = ScheduleItem::create([
                    'schedule_id' => $request->schedule_id,
                    'title' => $item['title'],
                    'thesis_id' => $thesisId,
                    'section_id' => $request->section_id,
                    'time_start' => $start,
                    'time_end' => $end,
                    'position' => $key,
                    'is_standart' => $item['is_standart'],
                    'type' => $item['type'],
                ]);
            }

            $scheduleItems->push($scheduleItem);
        }

        ScheduleItem::destroy($existingItems->pluck('id')->all());

        $scheduleItems->load([
            'thesis' => fn ($q) => $q->select(['thesis_id', 'title', 'authors', 'reporter', 'report_form', 'id', 'solicited_talk']),
            'scheduleItemTags' => fn ($q) => $q->select(['title_ru', 'title_en', 'color', 'schedule_item_id', 'id']),
        ]);

        return response()->json($scheduleItems);
    }
}
