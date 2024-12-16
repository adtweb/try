<?php

namespace App\Http\Controllers;

use App\Events\SchedulePublished;
use App\Http\Requests\ConferenceStoreRequest;
use App\Http\Requests\ConferenceUpdateRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Conferences\Actions\CreateConference;
use Src\Domains\Conferences\Actions\UpdateConference;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;
use Src\Domains\Conferences\Models\Subject;
use Src\Domains\Messenger\Enums\Role;
use Src\Domains\Schedule\Models\Schedule;
use Src\Domains\Schedule\Models\ScheduleItem;

class ConferenceController extends Controller
{
    public function organizerIndex(): View|Factory
    {
        $conferences = Conference::where('user_id', auth()->id())
            ->with(['organization', 'subjects'])
            ->orderByDesc('start_date')
            ->get();

        return view('conferences', [
            'title' => 'Мои мероприятия',
            'h1' => 'Организуемые мероприятия',
            'conferences' => $conferences,
        ]);
    }

    public function participantIndex(): View|Factory
    {
        $conferenceIds = participant()->participations->pluck('conference_id');

        $conferences = Conference::whereIn('id', $conferenceIds)
            ->with(['organization', 'subjects'])
            ->orderByDesc('start_date')
            ->get();

        return view('conferences', [
            'title' => 'Мои мероприятия',
            'h1' => 'Мероприятия в которых Вы участвуете',
            'conferences' => $conferences,
        ]);
    }

    public function subjectIndex(Subject $subject): View|Factory
    {
        //TODO pagination
        $conferences = Conference::query()
            ->whereHas('subjects', function ($query) use ($subject) {
                return $query->where('subjects.id', $subject->id);
            })
            ->with(['subjects', 'organization'])
            ->get();

        return view('conferences', [
            'title' => $subject->{'title_'.loc()},
            'h1' => $subject->{'title_'.loc()},
            'breadcrumbs' => $subject->{'title_'.loc()},
            'conferences' => $conferences,
        ]);
    }

    public function archive(): View|Factory
    {
        $h1 = __('pages.archive.h1');
        $title = __('pages.archive.title');

        $conferences = Conference::query()
            ->where('end_date', '<', now()->startOfDay())
            ->with('subjects', 'organization')
            ->orderBy('start_date')
            ->paginate();

        return view('conferences', [
            'title' => $title,
            'h1' => $h1,
            'breadcrumbs' => $h1,
            'conferences' => $conferences,
        ]);
    }

    public function create(): View|Factory
    {
        $organizations = Organization::all(['id', 'full_name_'.loc()]);

        return view('my.events.create', compact('organizations'));
    }

    public function store(
        ConferenceStoreRequest $request,
        CreateConference $createConference,
    ): JsonResponse {
        $conference = $createConference->handle($request);

        return response()->json($conference, Response::HTTP_CREATED);
    }

    public function show(Conference $conference): View|Factory
    {
        $conference->load(['sections' => fn ($q) => $q->with('moderators')]);

        $participation = user_participation($conference)?->load([
            'theses' => function ($query) {
                $query
                    ->with([
                        'scheduleItem' => fn ($q) => $q
                            ->with('schedule')
                            ->select(['thesis_id', 'time_start', 'time_end', 'schedule_id']),
                        'assets' => fn ($q) => $q->select(['id', 'title', 'path', 'thesis_id']),
                    ])
                    ->select(['theses.id', 'theses.title', 'thesis_id', 'theses.created_at', 'participation_id']);
            },
        ]);

        return view('conference', compact('conference', 'participation'));
    }

    public function edit(Conference $conference): View|Factory
    {
        $organizations = Organization::all(['id', 'full_name_'.loc()]);

        return view('my.events.personal.edit', compact('conference', 'organizations'));
    }

    public function update(
        Conference $conference,
        ConferenceUpdateRequest $request,
        UpdateConference $updateConference,
    ): JsonResponse {
        $updateConference->handle($conference, $request);

        return response()->json(['redirect' => route('conference.show', $conference->slug)], Response::HTTP_OK);
    }

    public function schedule(Conference $conference): View|Factory
    {
        if (auth()->user()?->canNot('viewSchedules', $conference) && ! $conference->schedule_is_published) {
            return view('schedule', compact('conference'));
        }

        $sections = Section::where('conference_id', $conference->id)->get(['id', 'slug', 'title_ru', 'title_en', 'conference_id']);

        $schedules = Schedule::where('conference_id', $conference->id)
            ->orderBy('date')
            ->get(['id', 'date', 'conference_id']);

        $scheduleItems = ScheduleItem::whereIn('section_id', $sections->pluck('id')->all())
            ->orderBy('time_start')
            ->orderBy('position')
            ->with([
                'thesis' => function ($q) use ($conference) {
                    $q->when($conference->asset_is_published, function ($q) {
                        $q->with(['assets' => fn ($q) => $q->where('is_approved', true)
                            ->select(['id', 'title', 'path', 'thesis_id'])]);
                    })
                        ->select(['solicited_talk', 'id', 'thesis_id', 'reporter', 'authors', 'report_form', 'title']);
                },
                'scheduleItemTags' => fn ($q) => $q->select(['title_ru', 'title_en', 'color', 'schedule_item_id']),
            ])
            ->get(['thesis_id', 'time_start', 'time_end', 'schedule_id', 'section_id', 'position', 'title', 'is_standart', 'id', 'type']);

        return view('schedule', compact('conference', 'sections', 'schedules', 'scheduleItems'));
    }

    public function publishSchedule(Conference $conference)
    {
        if (app()->isLocal()) {
            set_time_limit(300);
        }
        if ($conference->schedule_is_published) {
            return;
        }

        $conference->update(['schedule_is_published' => true]);

        event(new SchedulePublished($conference));
    }

    public function publishThesisAssets(Conference $conference)
    {
        $conference->asset_is_published = request('asset_is_published');
        $conference->save();
    }

    public function messenger(Conference $conference)
    {
        $role = auth()->id() === $conference->user_id
            ? Role::ORGANIZER->value
            : Role::MODERATOR->value;

        return view('my.chats', compact('conference', 'role'));
    }

    public function deleteLogo(Conference $conference)
    {
        if (! $conference->logo) {
            return;
        }

        if (Storage::disk('s3')->delete($conference->logo)) {
            $conference->update(['logo' => null]);
        } else {
            throw new \Exception('Не удалось удалить фотографию');
        }
    }
}
