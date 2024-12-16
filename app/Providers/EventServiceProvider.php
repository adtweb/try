<?php

namespace App\Providers;

use App\Events\ConferenceCreated;
use App\Events\OrganizationCreated;
use App\Events\SchedulePublished;
use App\Events\ThesisAssetCreated;
use App\Events\ThesisCreated;
use App\Events\ThesisCreating;
use App\Events\ThesisDeleted;
use App\Events\ThesisUpdatedByAuthor;
use App\Events\ThesisUpdatedByOrganizer;
use App\Events\ThesisUpdating;
use App\Listeners\CreateDefaultSection;
use App\Listeners\GenerateThesisId;
use App\Listeners\SendConferenceCreatedEmail;
use App\Listeners\SendOrganizationCreatedEmail;
use App\Listeners\SendSchedulePublishedNotifications;
use App\Listeners\SendThesisAssetCreatedNotification;
use App\Listeners\SendThesisCreatedNotification;
use App\Listeners\SendThesisDeletedNotification;
use App\Listeners\SendThesisUpdatedNotification;
use App\Listeners\SendThesisUpdatedNotificationToParticipant;
use App\Listeners\UpdateThesisId;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ConferenceCreated::class => [
            CreateDefaultSection::class,
            SendConferenceCreatedEmail::class,
        ],

        OrganizationCreated::class => [
            SendOrganizationCreatedEmail::class,
        ],

        ThesisCreating::class => [
            GenerateThesisId::class,
        ],

        ThesisUpdating::class => [
            UpdateThesisId::class,
        ],

        ThesisCreated::class => [
            SendThesisCreatedNotification::class,
        ],

        ThesisDeleted::class => [
            SendThesisDeletedNotification::class,
        ],

        ThesisUpdatedByAuthor::class => [
            SendThesisUpdatedNotification::class,
        ],

        ThesisUpdatedByOrganizer::class => [
            SendThesisUpdatedNotificationToParticipant::class,
        ],

        ThesisAssetCreated::class => [
            SendThesisAssetCreatedNotification::class,
        ],

        SchedulePublished::class => [
            SendSchedulePublishedNotifications::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
