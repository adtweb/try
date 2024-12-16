<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'password' => ['required'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
            'new_password_confirmation' => ['required'],
        ];
    }
}
