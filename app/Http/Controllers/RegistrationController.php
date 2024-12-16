<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRegisterRequest;
use App\Http\Requests\ParticipantRegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Src\Domains\Auth\Actions\CreateNewUser;
use Src\Domains\Auth\Actions\CreateOrganization;
use Src\Domains\Auth\Actions\CreateParticipant;

class RegistrationController extends Controller
{
    public function create(): View|Factory
    {
        return view('auth.register');
    }

    public function registerOrganization(
        OrganizationRegisterRequest $request,
        CreateNewUser $createUser,
        CreateOrganization $createOrganization
    ): void {
        $user = $createUser->handle($request);

        event(new Registered($user));

        $createOrganization->handle($request, $user);

        auth()->login($user, true);
    }

    public function registerParticipant(
        ParticipantRegisterRequest $request,
        CreateNewUser $createUser,
        CreateParticipant $createParticipant
    ): void {
        $user = $createUser->handle($request);

        event(new Registered($user));

        $createParticipant->handle($request, $user);

        auth()->login($user, true);
    }
}
