<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Src\Domains\Conferences\Models\Thesis;

class ThesisDeletedOrganizationNotification extends Notification
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
        $conference = $this->thesis->participation->conference;
        $locale = $conference->abstracts_lang->value;

        App::setLocale($locale);

        return (new MailMessage)
            ->subject(__('emails/notifications.thesis_deleted_organization_notification.subject'))
            ->line(__(
                'emails/notifications.thesis_deleted_organization_notification.text',
                [
                    'abstract_title' => $this->thesis->title,
                    'thesis_id' => $this->thesis->thesis_id,
                    'conference_title' => $conference->{'title_'.$locale},
                ],
            ))
            ->action(__('emails/notifications.thesis_deleted_organization_notification.action'), route('conference.show', $conference->slug));
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
