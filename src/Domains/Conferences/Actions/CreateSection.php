<?php

namespace Src\Domains\Conferences\Actions;

use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;

class CreateSection
{
    public function handle(array $sectionData, Conference $conference): Section
    {
        return Section::create([
            'conference_id' => $conference->id,
            'slug' => $sectionData['slug'],
            'title_ru' => $sectionData['title_ru'],
            'title_en' => $sectionData['title_en'],
        ]);
    }
}
