<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Src\Domains\Conferences\Models\Thesis;

class ThesisUpdatedNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject(__('emails/notifications.thesis_updated_notification.subject'))
            ->line(__('emails/notifications.thesis_updated_notification.1', [
                'thesis_title' => $this->thesis->title,
                'thesis_id' => $this->thesis->thesis_id,
            ]))
            ->action(
                __('emails/notifications.thesis_updated_notification.btn'),
                route('theses.show', [$this->thesis->participation->conference->slug, $this->thesis->id])
            );
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
