<?php

namespace App\Listeners;

use App\Events\ConferenceCreated;
use Src\Domains\Conferences\Models\Section;

class CreateDefaultSection
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ConferenceCreated $event): void
    {
        Section::create([
            'conference_id' => $event->conference->id,
            'slug' => 'main',
            'title_ru' => 'Основная секция',
            'title_en' => 'Main section',
        ]);
    }
}
