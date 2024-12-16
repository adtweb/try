<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\DB;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Participation;
use Src\Domains\Conferences\Models\Section;
use Src\Domains\Conferences\Models\Thesis;
use Src\Domains\Conferences\Models\ThesisAsset;
use Src\Domains\Schedule\Models\Schedule;
use Src\Domains\Schedule\Models\ScheduleItem;
use Symfony\Component\Console\Helper\ProgressBar;

class CloneConference extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clone-conference {slug}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            $conference = Conference::where('slug', $this->argument('slug'))->first();

            if (is_null($conference)) {
                $this->error('Конференция не найдена');

                return $this::FAILURE;
            }

            $slugCount = Conference::where('slug', 'like', $conference->slug.'_copy%')->count();

            do {
                $slugCount++;
                $newSlug = $slugCount >= 1 ? "{$conference->slug}_copy".$slugCount : "{$conference->slug}_copy";
            } while (Conference::where('slug', $newSlug)->exists());

            $duration = $conference->start_date->diffInDays($conference->end_date);

            $data = $conference->toArray();
            data_forget($data, 'id');
            data_forget($data, 'created_at');
            data_forget($data, 'updated_at');

            data_set($data, 'slug', $newSlug);
            data_set($data, 'start_date', today());
            data_set($data, 'end_date', today()->addDays($duration));
            data_set($data, 'thesis_accept_until', today()->addDays($duration));
            data_set($data, 'thesis_edit_until', today()->addDays($duration));
            data_set($data, 'assets_load_until', today()->addDays($duration));

            $newConference = Conference::create($data);

            // Subjects
            $newConference->subjects()->sync($conference->subjects->pluck(['id']));

            // Sections
            $sectionsCombinations = [];

            foreach ($conference->sections as $section) {
                $data = $section->toArray();

                data_forget($data, 'id');
                data_forget($data, 'created_at');
                data_forget($data, 'updated_at');

                data_set($data, 'conference_id', $newConference->id);

                $newSection = Section::create($data);

                $sectionsCombinations[$section->id] = $newSection->id;

                // Moderators

                $section->load('moderators');

                $moderables = DB::table('moderables')
                    ->where('moderable_type', 'section')
                    ->where('moderable_id', $section->id)
                    ->get();

                foreach ($moderables as $moderable) {
                    DB::table('moderables')
                        ->insert([
                            'user_id' => $moderable->user_id,
                            'moderable_id' => $newSection->id,
                            'moderable_type' => 'section',
                            'name' => $moderable->name ?? 'Имя модератора',
                            'surname' => $moderator->surname ?? 'Фамилия модератора',
                            'comment' => $moderable->comment,
                        ]);
                }
            }

            // Participations

            $participations = Participation::where('conference_id', $conference->id)->get();

            Thesis::unsetEventDispatcher();
            ThesisAsset::unsetEventDispatcher();

            $thesesCombinations = [];

            $this->withProgressBar(
                $participations->count(),
                function (ProgressBar $progress) use ($newConference, $sectionsCombinations, $participations, &$thesesCombinations) {
                    foreach ($participations as $participation) {
                        $data = $participation->toArray();

                        data_forget($data, 'id');
                        data_forget($data, 'created_at');
                        data_forget($data, 'updated_at');

                        data_set($data, 'conference_id', $newConference->id);

                        $newParticipation = Participation::create($data);

                        // Theses

                        $theses = Thesis::where('participation_id', $participation->id)->get();

                        foreach ($theses as $thesis) {
                            $data = $thesis->toArray();

                            data_forget($data, 'id');
                            data_forget($data, 'created_at');
                            data_forget($data, 'updated_at');

                            data_set($data, 'participation_id', $newParticipation->id);
                            data_set($data, 'section_id', $sectionsCombinations[$thesis->section_id]);

                            $newThesis = Thesis::create($data);

                            $thesesCombinations[$thesis->id] = $newThesis->id;

                            // Theses assets
                            $thesisAssets = ThesisAsset::where('thesis_id', $thesis->id)->get();

                            foreach ($thesisAssets as $thesisAsset) {
                                $data = $thesisAsset->toArray();

                                data_forget($data, 'id');
                                data_forget($data, 'created_at');
                                data_forget($data, 'updated_at');

                                data_set($data, 'thesis_id', $newThesis->id);

                                ThesisAsset::create($data);
                            }
                        }

                        $progress->advance();
                    }
                });

            // Schedules
            $schedules = Schedule::where('conference_id', $conference->id)
                ->orderBy('date')
                ->get();

            foreach ($schedules as $key => $schedule) {
                $data = $schedule->toArray();

                data_forget($data, 'id');
                data_forget($data, 'created_at');
                data_forget($data, 'updated_at');

                data_set($data, 'conference_id', $newConference->id);
                data_set($data, 'date', $newConference->start_date->addDays($key));

                $newSchedule = Schedule::create($data);

                // ScheduleItems

                $scheduleItems = DB::table('schedule_items')->where('schedule_id', $schedule->id)->get();

                foreach ($scheduleItems as $scheduleItem) {
                    $data = json_decode(json_encode($scheduleItem), true);

                    data_forget($data, 'id');
                    data_forget($data, 'created_at');
                    data_forget($data, 'updated_at');

                    data_set($data, 'schedule_id', $newSchedule->id);
                    data_set($data, 'section_id', $sectionsCombinations[$scheduleItem->section_id]);
                    data_set($data, 'thesis_id', is_null($scheduleItem->thesis_id) ? null : $thesesCombinations[$scheduleItem->thesis_id]);

                    ScheduleItem::create($data);
                }
            }
        });
    }
}
