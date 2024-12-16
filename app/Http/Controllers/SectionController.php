<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionMassUpdateRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Src\Domains\Conferences\Actions\UpdateConferenceSections;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;

class SectionController extends Controller
{
    public function index(Conference $conference): View|Factory
    {
        $sections = $conference->sections
            ->loadExists('theses')
            ->load(['moderators' => fn ($q) => $q->with('participant')]);

        return view('my.events.personal.sections', compact('conference', 'sections'));
    }

    public function massUpdate(
        Conference $conference,
        SectionMassUpdateRequest $request,
        UpdateConferenceSections $updateConferenceSections,
    ): JsonResponse {
        $updateConferenceSections->handle($conference, $request);

        $sections = Section::query()
            ->where('conference_id', $conference->id)
            ->with(['moderators' => fn ($q) => $q->with('participant')])
            ->get(['id', 'slug', 'title_ru', 'title_en']);

        return response()->json($sections);
    }
}
