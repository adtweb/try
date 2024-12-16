<?php

namespace App\Http\Controllers;

use App\Events\ThesisCreated;
use App\Events\ThesisUpdatedByAuthor;
use App\Events\ThesisUpdatedByOrganizer;
use App\Http\Requests\ThesisStoreRequest;
use App\Http\Requests\ThesisUpdateRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Src\Domains\Conferences\Actions\CreateThesis;
use Src\Domains\Conferences\Actions\UpdateThesis;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Thesis;

class ThesisController extends Controller
{
    public function indexByConference(Conference $conference): View|Factory
    {
        $allowedSections = auth()->user()
            ->moderatedSections
            ->where('conference_id', $conference->id)
            ->pluck('id');

        // Если пользователь модератор секции, то получит данные только по свои секциям.
        // В остальных случаях он либо организатор, либо модератор конференции и получает все
        $conference->load([
            'theses' => function (HasManyThrough $query) use ($allowedSections) {
                $query
                    ->with(['assets' => fn ($q) => $q->select(['id', 'path', 'title', 'thesis_id'])])
                    ->when($allowedSections->isNotEmpty(), function ($collection) use ($allowedSections) {
                        return $collection->whereIn('section_id', $allowedSections->toArray());
                    })
                    ->withTrashed()
                    ->select(['theses.id', 'theses.title', 'thesis_id', 'theses.created_at', 'authors', 'section_id', 'deleted_at', 'report_form']);
            },
            'sections' => function ($query) use ($allowedSections) {
                $query
                    ->when($allowedSections->isNotEmpty(), function ($collection) use ($allowedSections) {
                        return $collection->whereIn('id', $allowedSections->toArray());
                    })
                    ->select(['sections.id', 'slug', 'conference_id']);
            },
        ]);

        // dd($conference->theses);

        return view('my.events.personal.theses', compact('conference'));
    }

    public function show(Conference $conference, Thesis $thesis): View|Factory
    {
        $thesis->load('participation');

        return view('my.events.personal.thesis', compact('conference', 'thesis'));
    }

    public function showPublicly(Conference $conference, Thesis $thesis): View|Factory
    {
        abort_if(! $conference->schedule_is_published, Response::HTTP_NOT_FOUND);

        $thesis->load('participation');

        if ($conference->asset_is_published) {
            $thesis->load([
                'assets' => function ($q) {
                    $q->where('is_approved', true)
                        ->select(['id', 'path', 'title', 'thesis_id']);
                },
            ]);
        }

        return view('thesis', compact('conference', 'thesis'));
    }

    public function create(Conference $conference): View|Factory
    {
        $participation = $conference->participationByUser();

        return view('my.events.theses.create', compact('conference', 'participation'));
    }

    public function store(
        Conference $conference,
        ThesisStoreRequest $request,
        CreateThesis $createThesis,
    ): JsonResponse {
        $thesis = $createThesis->handle($request);

        event(new ThesisCreated($thesis));

        return response()->json(['redirect' => route('conference.show', $conference->slug)]);
    }

    public function edit(Conference $conference, Thesis $thesis): View|Factory
    {
        $participation = $conference->participationByUser();

        return view('my.events.theses.edit', compact('conference', 'participation', 'thesis'));
    }

    public function update(
        Conference $conference,
        Thesis $thesis,
        ThesisUpdateRequest $request,
        UpdateThesis $updateThesis,
    ): JsonResponse {
        $updateThesis->handle($thesis, $request);

        if ($thesis->participation->participant_id === participant()->id) {
            event(new ThesisUpdatedByAuthor($thesis));
        } else {
            event(new ThesisUpdatedByOrganizer($thesis));
        }

        return response()->json(['redirect' => route('conference.show', $conference->slug)]);
    }

    public function destroy(Thesis $thesis): JsonResponse
    {
        $thesis->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
