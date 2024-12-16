<?php

namespace App\Listeners;

use App\Notifications\ThesisUpdatedByOrganizerNotification;

class SendThesisUpdatedNotificationToParticipant
{
    public function handle(object $event): void
    {
        $event->thesis->participation->participant->user
            ->notify(new ThesisUpdatedByOrganizerNotification($event->thesis));
    }
}
