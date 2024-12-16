<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Src\Domains\Conferences\Models\ThesisAsset;

class ThesisAssetCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ThesisAsset $asset) {}

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
        $thesis = $this->asset->thesis;

        return (new MailMessage)
            ->subject(__('emails/notifications.thesis_asset_created_notification.subject'))
            ->line(__('emails/notifications.thesis_asset_created_notification.1', [
                'conference_title' => $thesis->participation->conference->{'title_'.loc()},
                'thesis_title' => $thesis->title,
                'thesis_id' => $thesis->thesis_id,
            ]))
            ->action(
                __('emails/notifications.thesis_asset_created_notification.btn'),
                config('filesystems.disks.s3.base_url').$this->asset->path
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
