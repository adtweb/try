<?php

namespace Src\Domains\Conferences\Actions;

use Illuminate\Http\Request;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;

class UpdateConferenceSections
{
    public function __construct(private CreateSection $createSection) {}

    public function handle(Conference $conference, Request $request): void
    {
        $oldSections = $conference->sections;

        $newSections = collect($request->get('sections'));

        $existingSections = $newSections->whereNotNull('id');
        $creatingSections = $newSections->whereNull('id');

        foreach ($existingSections as $section) {
            Section::find($section['id'])
                ->update([
                    'slug' => $section['slug'],
                    'title_ru' => $section['title_ru'],
                    'title_en' => $section['title_en'],
                ]);
        }

        if ($oldSections->count() > $existingSections->count()) {
            $deletingSectionsIds = $oldSections->pluck('id')->diff($existingSections->pluck('id'));

            foreach ($deletingSectionsIds as $key => $id) {
                $section = Section::withCount('theses')->find($id);

                if ($section->theses_count > 0) {
                    data_forget($deletingSectionsIds, $key);
                }
            }

            if (count($deletingSectionsIds) > 0) {
                Section::whereIn('id', $deletingSectionsIds)->delete();
            }
        }

        foreach ($creatingSections as $section) {
            $this->createSection->handle($section, $conference);
        }
    }
}
