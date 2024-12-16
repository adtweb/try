<?php

namespace App\Listeners;

use App\Notifications\ThesisDeletedOrganizationNotification;
use App\Notifications\ThesisDeletedParticipantNotification;
use Src\Domains\Conferences\Models\Section;

class SendThesisDeletedNotification
{
    public function handle(object $event): void
    {
        $conference = $event->thesis->participation->conference;
        $partcicpantUser = $event->thesis->participation->participant->user;
        $organizationUser = $conference->user;

        $organizationUser->notify(new ThesisDeletedOrganizationNotification($event->thesis));
        $partcicpantUser->notify(new ThesisDeletedParticipantNotification($event->thesis));

        foreach ($conference->moderators as $moderator) {
            $moderator->notify(new ThesisDeletedOrganizationNotification($event->thesis));
        }

        if (! is_null($event->thesis->section_id)) {
            $section = Section::find($event->thesis->section_id);

            foreach ($section->moderators as $moderator) {
                $moderator->notify(new ThesisDeletedOrganizationNotification($event->thesis));
            }
        }
    }
}
