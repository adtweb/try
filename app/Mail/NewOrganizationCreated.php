<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Src\Domains\Auth\Models\Organization;

class NewOrganizationCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Organization $organization) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if (str_contains(config('app.url'), 'dev')) {
            $subject = '[DEV] Создана новая организация';
        } else {
            $subject = 'Создана новая организация';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'mail.new_organization_created',
        );
    }
}
