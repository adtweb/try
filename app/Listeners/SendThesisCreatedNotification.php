<?php

namespace App\Listeners;

use App\Events\ThesisCreated;
use App\Notifications\ThesisCreatedOrganizationNotification;
use App\Notifications\ThesisCreatedParticipantNotification;
use Src\Domains\Conferences\Models\Section;

class SendThesisCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ThesisCreated $event): void
    {
        $conference = $event->thesis->participation->conference;
        $partcicpantUser = $event->thesis->participation->participant->user;
        $organizationUser = $conference->user;

        $organizationUser->notify(new ThesisCreatedOrganizationNotification($event->thesis));
        $partcicpantUser->notify(new ThesisCreatedParticipantNotification($event->thesis));

        if (! is_null($event->thesis->section_id)) {
            $section = Section::find($event->thesis->section_id);

            foreach ($section->moderators as $moderator) {
                $moderator->notify(new ThesisCreatedOrganizationNotification($event->thesis));
            }
        }
    }
}
