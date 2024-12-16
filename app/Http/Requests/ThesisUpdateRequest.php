<?php

namespace App\Http\Requests;

use App\Rules\MaxStripTagsCharacters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Src\Domains\Conferences\Enums\ConferenceReportForm;

class ThesisUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('thesis'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'section_id' => ['sometimes', 'required', 'int'],
            'report_form' => ['required', Rule::enum(ConferenceReportForm::class)],
            'solicited_talk' => ['required', 'boolean'],
            'title' => ['required', 'string', new MaxStripTagsCharacters(230)],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*.name_ru' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[а-яА-Я \-_]+$/u'],
            'authors.*.surname_ru' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[а-яА-Я \-_]+$/u'],
            'authors.*.middle_name_ru' => ['sometimes', 'nullable', 'string', 'max:255', 'regex:/^[а-яА-Я \-_]+$/u'],
            'authors.*.name_en' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z \-_]+$/u'],
            'authors.*.surname_en' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z \-_]+$/u'],
            'authors.*.middle_name_en' => ['sometimes', 'nullable', 'string', 'max:255', 'regex:/^[a-zA-Z \-_]+$/u'],
            'authors.*.affiliations' => ['nullable', 'array'],
            'authors.*.affiliations.*.id' => ['nullable'],
            'authors.*.affiliations.*.title_ru' => [
                'sometimes',
                'required_unless:authors.*.affiliations.*.no_affiliation,true',
                'string',
                'nullable',
                'max:255',
            ],
            'authors.*.affiliations.*.title_en' => [
                'sometimes',
                'required_unless:authors.*.affiliations.*.no_affiliation,true',
                'nullable',
                'string',
                'max:255',
            ],
            'authors.*.affiliations.*.country' => ['array', 'nullable'],
            'authors.*.affiliations.*.country.id' => ['sometimes', 'required', 'exists:countries,id'],
            'reporter' => ['required', 'array'],
            'reporter.id' => ['required', 'int'],
            'reporter.is_young' => ['required', 'boolean'],
            'contact' => ['required', 'array'],
            'contact.id' => ['required', 'int'],
            'contact.email' => ['required', 'email'],
            'text' => ['required', 'string', new MaxStripTagsCharacters($this->route('conference')->max_thesis_characters)],
        ];
    }

    public function attributes()
    {
        return [
            'authors.*.affiliations.*.country.id' => 'Страна аффилиации',
        ];
    }

    public function messages(): array
    {
        return [
            'authors.*.affiliations.*.country.id.required' => 'Поле :attribute должно быть выбрано из выпадающего списка',
        ];
    }

    protected function prepareForValidation() {}

    protected function passedValidation()
    {
        if (Gate::denies('updateAbstracts', $this->route('conference'))) {
            abort(Response::HTTP_BAD_REQUEST, 'Редактирование тезисов недоступно');
        }
    }
}
