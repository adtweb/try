<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeratorDestroyRequest;
use App\Http\Requests\ModeratorStoreRequest;
use App\Notifications\CreatedAsModerator;
use App\Notifications\InvitedAsModerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;

class ModeratorController extends Controller
{
    public function store(Conference $conference, ModeratorStoreRequest $request): JsonResponse
    {
        $section = Section::find($request->get('section_id'));

        $user = User::where(['email' => $request->get('email')])->first();

        if (is_null($user)) {
            $password = str()->random(10);
            $user = User::create([
                'email' => $request->get('email'),
                'password' => bcrypt($password),
                'email_verified_at' => now(),
            ]);

            Participant::create(['user_id' => $user->id]);

            $user->notify(new CreatedAsModerator($section, $password));
        } else {
            $user->notify(new InvitedAsModerator($section));

            if (! Participant::where('user_id', $user->id)->exists()) {
                Participant::create(['user_id' => $user->id]);
            }
        }

        $section->moderators()->sync([$user->id => [
            'comment' => $request->get('comment'),
        ]], false);

        $data = $section->moderators->load('participant');

        return response()->json($data);
    }

    public function destroy(Conference $conference, ModeratorDestroyRequest $request): JsonResponse
    {
        $section = Section::find($request->validated('section_id'));

        if ($section->conference_id !== $conference->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $section->moderators()->detach($request->validated('user_id'));

        return response()->json($section->moderators->load('participant'));
    }
}
