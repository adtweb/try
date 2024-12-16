<?php

namespace App\Listeners;

use App\Events\SchedulePublished;
use App\Mail\SchedulePublishedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Src\Domains\Conferences\Models\Participation;

class SendSchedulePublishedNotifications implements ShouldQueue
{
    public function handle(SchedulePublished $event): void
    {
        $conference = $event->conference;
        $participations = Participation::where('conference_id', $conference->id)
            ->with(['participant' => fn ($q) => $q->select(['participants.id', 'participants.user_id'])
                ->with(['user' => fn ($q) => $q->select(['users.email', 'users.id'])]),
            ])
            ->withExists('theses')
            ->lazy(50);

        foreach ($participations as $participation) {
            if (! $participation->theses_exists) {
                continue;
            }

            $participation->participant->user;
            Mail::to($participation->participant->user->email)
                ->send(new SchedulePublishedEmail($event->conference, $participation));
        }
    }
}
