<?php

namespace App\Listeners;

use App\Events\OrganizationCreated;
use App\Mail\NewOrganizationCreated;
use Illuminate\Support\Facades\Mail;

class SendOrganizationCreatedEmail
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
    public function handle(OrganizationCreated $event): void
    {
        Mail::to(['l.surovitsky@sudo.team', 'andrei.kosterov@gmail.com'])
            ->send(new NewOrganizationCreated($event->organization));
    }
}
