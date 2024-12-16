<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Src\Domains\Conferences\Models\Conference;

class NewConferenceCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public Conference $conference;

    public function __construct(public int $conferenceId)
    {
        $this->conference = Conference::find($this->conferenceId);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if (str_contains(config('app.url'), 'dev')) {
            $subject = '[DEV] Создана новая конференция';
        } else {
            $subject = 'Создана новая конференция';
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
            view: 'mail.new_conference_created',
        );
    }
}
