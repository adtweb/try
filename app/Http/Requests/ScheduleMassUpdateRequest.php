<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Src\Domains\Conferences\Models\Section;

class ScheduleMassUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $section = Section::find($this->section_id);

        return Gate::allows('edit-section-schedule', $section);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['nullable', 'array'],
            'items.*.duration' => ['required', 'integer', 'min:0'],
            'items.*.title' => ['required', 'string', 'max:255'],
            'items.*.is_standart' => ['required', 'in:0,1'],
        ];
    }
}
