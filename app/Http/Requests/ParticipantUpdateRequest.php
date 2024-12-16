<?php

namespace App\Http\Requests;

use App\Rules\AffiliationCharactersBothLanguages;
use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Dimensions;

class ParticipantUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name_ru' => ['nullable', 'max:255', 'string', 'regex:~[а-яА-Я0-9\-_ ]+~u'],
            'surname_ru' => ['nullable', 'max:255', 'string', 'regex:~[а-яА-Я0-9\-_ ]+~u'],
            'middle_name_ru' => ['nullable', 'max:255', 'string', 'regex:~[а-яА-Я0-9\-_ ]+~u'],
            'name_en' => ['nullable', 'max:255', 'string', 'regex:~[a-zA-Z0-9\-_ ]+~u'],
            'surname_en' => ['nullable', 'max:255', 'string', 'regex:~[a-zA-Z0-9\-_ ]+~u'],
            'middle_name_en' => ['nullable', 'max:255', 'string', 'regex:~[a-zA-Z0-9\-_ ]+~u'],
            'phone' => ['nullable', 'max:50', 'string', new Phone],
            'image' => ['nullable', 'image', 'max:2048', new Dimensions(['min_width' => 200, 'min_height' => 200]), 'mimes:jpg,png,webp'],
            'affiliations' => ['nullable', 'array'],
            'affiliations.*' => [new AffiliationCharactersBothLanguages],
            'affiliations.*.id' => ['nullable'],
            'affiliations.*.title_ru' => ['required', 'string', 'max:255'],
            'affiliations.*.title_en' => ['required', 'string', 'max:255'],
            'affiliations.*.country' => ['array', 'nullable'],
            'affiliations.*.country.id' => ['sometimes', 'required', 'exists:countries,id'],
            'orcid_id' => ['nullable', 'max:50', 'regex:~\w{4}-\w{4}-\w{4}-\w{4}~u'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name_ru' => 'Имя (RU)',
            'surname_ru' => 'Фамилия (RU)',
            'middle_name_ru' => 'Отчество (RU)',
            'name_en' => 'First name (ENG)',
            'surname_en' => 'Surname (ENG)',
            'middle_name_en' => 'Middle name (ENG)',
            'phone' => 'Телефон',
            'orcid_id' => 'ORCID ID',
            'website' => 'Сайт',
            'image' => 'Фото',
        ];
    }

    public function messages(): array
    {
        return [
            'name_ru.regex' => 'Недопустимые символы в поле :attribute',
            'surname_ru.regex' => 'Недопустимые символы в поле :attribute',
            'middle_name_ru.regex' => 'Недопустимые символы в поле :attribute',
            'name_en.regex' => 'Недопустимые символы в поле :attribute',
            'surname_en.regex' => 'Недопустимые символы в поле :attribute',
            'middle_name_en.regex' => 'Недопустимые символы в поле :attribute',
            'image.dimensions' => 'Минимальный размер изображения 200x200px',
        ];
    }
}
