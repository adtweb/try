<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class SectionMassUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('massSectionUpdate', $this->route('conference'));
    }

    public function rules(): array
    {
        return [
            'sections' => ['required', 'array'],
            'sections.0' => ['required', 'array'],
            'sections.*.id' => ['sometimes', 'exists:sections,id'],
            'sections.*.slug' => ['required', 'string', 'max:15'],
            'sections.*.title_ru' => ['required', 'string', 'regex:~^[а-яА-Я0-9\-_ ]+$~u'],
            'sections.*.title_en' => ['required', 'string', 'regex:~^[a-zA-Z0-9\-_ ]+$~u'],
        ];
    }

    public function attributes(): array
    {
        return [
            'sections.*.slug' => __('validation.attributes.acronim'),
        ];
    }

    protected function passedValidation()
    {
        $sections = collect($this->get('sections'));

        $slugDuplicates = $sections->pluck('slug')->duplicates();

        if ($slugDuplicates->isNotEmpty()) {
            $messages = [];

            foreach ($slugDuplicates as $key => $value) {
                $messages["sections.$key.slug"] = __('validation.unique', ['attribute' => __('validation.attributes.acronim')]);
            }

            throw ValidationException::withMessages($messages);
        }
    }
}
