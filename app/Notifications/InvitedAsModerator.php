<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;

class InvitedAsModerator extends Notification implements ShouldQueue
{
    use Queueable;

    public Conference $conference;

    public function __construct(public Section $section)
    {
        $this->conference = $section->conference;
    }

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
            ->subject(__('emails/notifications.invited_as_moderator.subject'))
            ->line(__('emails/notifications.invited_as_moderator.1', [
                'conference_title' => $this->conference->{'title_'.loc()},
            ]))
            ->action(__('emails/notifications.invited_as_moderator.btn'), route('conference.participations', $this->conference->slug));
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
