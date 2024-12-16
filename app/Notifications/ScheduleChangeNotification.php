<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class ScheduleChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Collection $theses) {}

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
        $conference = $this->theses->load('participation')->first()->participation->conference;
        $text = view('mail.schedule_change_notification', [
            'conference' => $conference,
            'theses' => $this->theses,
        ])->render();

        return (new MailMessage)
            ->subject(__('emails/notifications.schedule_change_notification.subject'))
            ->line(new HtmlString($text))
            ->action(__('emails/notifications.schedule_change_notification.btn'), route('conference.schedule', $conference->slug));
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
