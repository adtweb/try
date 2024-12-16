<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipationStoreRequest;
use App\Http\Requests\ParticipationUpdateRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Src\Domains\Conferences\Actions\CreateParticipation;
use Src\Domains\Conferences\Actions\UpdateParticipation;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Participation;

class ParticipationController extends Controller
{
    public function indexByConference(Conference $conference): View|Factory
    {
        return view('my.events.personal.participations', compact('conference'));
    }

    public function create(Conference $conference): View|Factory
    {
        return view('my.events.participate', compact('conference'));
    }

    public function store(
        Conference $conference,
        ParticipationStoreRequest $request,
        CreateParticipation $createParticipation
    ): JsonResponse {
        $exists = Participation::where('conference_id', $conference->id)
            ->where('participant_id', $request->participant_id)
            ->exists();

        if ($exists) {
            throw new Exception('Участник уже учавствует в событии');
        }

        $participation = $createParticipation->handle($request);

        return response()->json(['redirect' => route('conference.show', $conference->slug)]);
    }

    public function edit(Conference $conference): View|Factory
    {
        $participation = user_participation($conference);

        abort_if(is_null($participation), Response::HTTP_NOT_FOUND, 'Вы не подавали заявку на мероприятие');

        return view('my.events.edit-participation', compact('conference', 'participation'));
    }

    public function update(
        Conference $conference,
        ParticipationUpdateRequest $request,
        UpdateParticipation $updateParticipation,
    ): JsonResponse {
        $participation = user_participation($conference);

        $participation = $updateParticipation->handle($participation, $request);

        return response()->json(['redirect' => route('conference.show', $conference->slug)]);
    }
}
