<?php

namespace App\Notifications;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Src\Domains\Conferences\Models\Thesis;

class ThesisUpdatedByOrganizerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Thesis $thesis) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $conference = $this->thesis->load('participation')->participation->conference;
        $locale = $conference->abstracts_lang->value;

        App::setLocale($locale);

        $authors = $this->thesis->authors;
        $thesisId = $this->thesis->thesis_id;
        $title = $this->thesis->title;
        $reporter = $this->thesis->reporter;
        $contact = $this->thesis->contact;
        $text = $this->thesis->text;

        $pdf = Pdf::loadView('pdf.thesis', compact('conference', 'authors', 'thesisId', 'title', 'reporter', 'contact', 'text'));

        return (new MailMessage)
            ->subject(__('emails/notifications.thesis_updated_by_organizer_notification.subject'))
            ->line(__('emails/notifications.thesis_updated_by_organizer_notification.1'))
            ->action(
                __('emails/notifications.thesis_updated_by_organizer_notification.btn'),
                route('theses.edit', [$conference->slug, $this->thesis->id])
            )
            ->attachData($pdf->output(), "{$this->thesis->thesis_id}.pdf");
    }
}
