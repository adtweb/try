<?php

namespace Src\Domains\Auth\Actions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Src\Domains\Auth\Models\User;

class CreateNewUser
{
    public function handle(FormRequest $request): User
    {
        return User::create([
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
    }
}
