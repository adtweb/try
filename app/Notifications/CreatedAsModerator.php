<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;

class CreatedAsModerator extends Notification implements ShouldQueue
{
    use Queueable;

    public Conference $conference;

    public function __construct(public Section $section, public string $password)
    {
        $this->conference = $section->conference;
    }

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
            ->subject(__('emails/notifications.created_as_moderator.subject'))
            ->line(__('emails/notifications.created_as_moderator.1', [
                'conference_title' => $this->conference->{'title_'.loc()},
            ]))
            ->line(__('emails/notifications.created_as_moderator.2'))
            ->line(__('emails/notifications.created_as_moderator.3', ['email' => $notifiable->email]))
            ->line(__('emails/notifications.created_as_moderator.4', ['password' => $this->password]))
            ->action(
                __('emails/notifications.created_as_moderator.btn'),
                route('conference.participations', $this->conference->slug)
            );
    }
}
