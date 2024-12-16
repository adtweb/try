<?php

namespace App\Listeners;

use App\Events\ThesisAssetCreated;
use App\Notifications\ThesisAssetCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Src\Domains\Conferences\Models\Section;

class SendThesisAssetCreatedNotification implements ShouldQueue
{
    public function handle(ThesisAssetCreated $event): void
    {
        $conference = $event->asset->thesis->participation->conference;
        $organizationUser = $conference->user;

        $organizationUser->notify(new ThesisAssetCreatedNotification($event->asset));

        foreach ($conference->moderators as $moderator) {
            $moderator->notify(new ThesisAssetCreatedNotification($event->asset));
        }

        if (! is_null($event->asset->thesis->section_id)) {
            $section = Section::find($event->asset->thesis->section_id);

            foreach ($section->moderators as $moderator) {
                $moderator->notify(new ThesisAssetCreatedNotification($event->asset));
            }
        }
    }
}
