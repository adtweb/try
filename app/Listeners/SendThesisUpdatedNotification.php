<?php

namespace App\Listeners;

use App\Events\ThesisUpdatedByAuthor;
use App\Notifications\ThesisUpdatedNotification;
use Src\Domains\Conferences\Models\Section;

class SendThesisUpdatedNotification
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
    public function handle(ThesisUpdatedByAuthor $event): void
    {
        $thesis = $event->thesis;
        $conference = $thesis->participation->conference;

        $organizationUser = $conference->user;

        $organizationUser->notify(new ThesisUpdatedNotification($thesis));

        if (! is_null($thesis->section_id)) {
            $section = Section::find($thesis->section_id);

            foreach ($section->moderators as $moderator) {
                $moderator->notify(new ThesisUpdatedNotification($thesis));
            }
        }
    }
}
