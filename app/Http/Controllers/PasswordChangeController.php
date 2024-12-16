<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordChangeController extends Controller
{
    public function edit(): View|Factory
    {
        return view('my.password');
    }

    public function update(PasswordUpdateRequest $request): JsonResponse
    {
        if (! Hash::check($request->get('password'), auth()->user()->password)) {
            throw ValidationException::withMessages(['password' => __('auth.password')]);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return response()->json(['ok' => true]);
    }
}
