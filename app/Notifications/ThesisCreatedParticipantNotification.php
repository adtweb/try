<?php

namespace App\Notifications;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Src\Domains\Conferences\Models\Thesis;

class ThesisCreatedParticipantNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Thesis $thesis) {}

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
            ->subject(__('emails/notifications.thesis_created_participant_notification.subject'))
            ->line(__(
                'emails/notifications.thesis_created_participant_notification.text',
                [
                    'abstract_title' => $this->thesis->title,
                    'abstract_id' => $this->thesis->thesis_id,
                ],
            ))
            ->action(
                __('emails/notifications.thesis_created_participant_notification.action'),
                route('theses.edit', [$conference->slug, $this->thesis->id])
            )
            ->salutation(__(
                'emails/notifications.thesis_created_participant_notification.salutation',
                ['conference_title' => $conference->{'title_'.$locale}]
            ))
            ->attachData($pdf->output(), "Abstract {$this->thesis->thesis_id}.pdf");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
