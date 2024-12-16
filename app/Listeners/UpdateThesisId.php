<?php

namespace App\Listeners;

use Src\Domains\Conferences\Models\Section;
use Src\Domains\Conferences\Models\Thesis;

class UpdateThesisId
{
    public function __construct() {}

    public function handle(object $event): void
    {
        $thesis = $event->thesis;
        $conference = $thesis->load(['participation'])->participation->conference;

        if ($thesis->isDirty('section_id')) {
            $lastThesis = Thesis::where('section_id', $thesis->section_id)
                ->withTrashed()
                ->orderByDesc('id')
                ->first();

            if (is_null($lastThesis)) {
                $number = 1;
            } else {
                $number = ((int) substr($lastThesis->thesis_id, -3)) + 1;
            }

            $section = Section::find($thesis->section_id);
            $thesis->thesis_id = sprintf(
                '%s-%s%s',
                $conference->slug,
                $section->slug,
                str_pad($number, 3, '0', STR_PAD_LEFT)
            );
        }
    }
}
