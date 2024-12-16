<?php

namespace Src\Domains\Conferences\Actions;

use Illuminate\Http\Request;
use Src\Domains\Conferences\Models\Conference;

class CreateConferenceSections
{
    public function __construct(private CreateSection $createSection) {}

    public function handle(Request $request, Conference $conference): void
    {
        foreach ($request->get('sections') as $section) {
            $this->createSection->handle($section, $conference);
        }
    }
}
