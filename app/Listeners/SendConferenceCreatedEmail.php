<?php

namespace App\Listeners;

use App\Events\ConferenceCreated;
use App\Mail\NewConferenceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendConferenceCreatedEmail implements ShouldQueue
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
    public function handle(ConferenceCreated $event): void
    {
        Mail::to(['l.surovitsky@sudo.team', 'andrei.kosterov@gmail.com'])
            ->send(new NewConferenceCreated($event->conference->id));
    }
}
