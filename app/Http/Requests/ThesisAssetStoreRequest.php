<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;
use Src\Domains\Conferences\Enums\ThesisAssetTitle;

class ThesisAssetStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('changeThesisAssets', $this->route('conference'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:20480', 'mimetypes:application/pdf'],
            'title' => ['required', new Enum(ThesisAssetTitle::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimetypes' => 'Файл должен быть в формате PDF',
            'file.max' => 'Размер файла не должен превышать 20 МБ',
        ];
    }
}
