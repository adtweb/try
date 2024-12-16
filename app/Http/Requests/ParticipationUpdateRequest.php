<?php

namespace App\Http\Requests;

use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Src\Domains\Conferences\Enums\ParticipationType;

class ParticipationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $participation = user_participation($this->route('conference'));

        return (bool) $participation;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_ru' => ['required', 'string', 'max:255', 'regex:/^[а-яА-Я \-_]+$/u'],
            'surname_ru' => ['required', 'string', 'max:255', 'regex:/^[а-яА-Я \-_]+$/u'],
            'middle_name_ru' => ['nullable', 'string', 'max:255', 'regex:/^[а-яА-Я \-_]+$/u'],
            'name_en' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z \-_]+$/u'],
            'surname_en' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z \-_]+$/u'],
            'middle_name_en' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z \-_]+$/u'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', new Phone],
            'affiliations' => ['nullable', 'array'],
            'affiliations.*.id' => ['nullable'],
            'affiliations.*.title_ru' => ['required', 'string', 'max:255', 'regex:~[а-яА-Я0-9\-_ ]+~u'],
            'affiliations.*.title_en' => ['required', 'string', 'max:255', 'regex:~[a-zA-Z0-9\-_ ]+~u'],
            'affiliations.*.country' => ['array', 'nullable'],
            'affiliations.*.country.id' => ['sometimes', 'required', 'exists:countries,id'],
            'orcid_id' => ['nullable', 'string', 'regex:/^\w{4}-\w{4}-\w{4}-\w{4}$/'],
            'website' => ['nullable', 'url', 'max:255'],
            'participation_type' => ['required', Rule::enum(ParticipationType::class)],
            'is_young' => ['required', 'boolean'],
        ];
    }

    protected function passedValidation()
    {
        if ($this->route('conference')->thesis_edit_until->isPast()) {
            abort(Response::HTTP_BAD_REQUEST, 'Заявку уже нельзя редактировать');
        }
    }
}
