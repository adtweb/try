<?php

namespace App\Http\Requests;

use App\Enums\Timezone;
use App\Rules\ConferenceDiscount;
use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Dimensions;
use Src\Domains\Conferences\Enums\AbstractsFormat;
use Src\Domains\Conferences\Enums\AbstractsLanguage;
use Src\Domains\Conferences\Enums\ConferenceFormat;
use Src\Domains\Conferences\Enums\ConferenceLanguage;
use Src\Domains\Conferences\Enums\ConferenceReportForm;
use Src\Domains\Conferences\Enums\ParticipantsNumber;

class ConferenceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->route('conference')->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'title_ru' => ['required', 'string', 'max:250', 'regex:/^[^a-z]+$/u'],
            'title_en' => ['required', 'string', 'max:250', 'regex:/^[^а-яА-Я]+$/u'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'conference_type_id' => ['required', 'in:'.conference_types()->pluck('id')->join(',')],
            'format' => ['required',  Rule::enum(ConferenceFormat::class)],
            'with_foreign_participation' => ['required', 'boolean'],
            'need_site' => ['required', 'boolean'],
            'subjects' => ['required', 'array'],
            'subjects.*' => ['required', 'int', 'in:'.subjects()->pluck('id')->join(',')],
            'image' => ['nullable', 'image', 'max:2048', new Dimensions(['min_width' => 200, 'min_height' => 200]), 'mimes:jpg,png,webp'],
            'website' => ['nullable', 'url', 'max:255'],
            'co-organizers' => ['nullable', 'array'],
            'co-organizers.*' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', new Phone],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'timezone' => ['required', Rule::enum(Timezone::class)],
            'description_ru' => ['required', 'string', 'max:1000'],
            'description_en' => ['required', 'string', 'max:1000'],
            'lang' => ['required', Rule::enum(ConferenceLanguage::class)],
            'participants_number' => ['required', Rule::enum(ParticipantsNumber::class)],
            'report_form' => ['required',  Rule::enum(ConferenceReportForm::class)],
            'whatsapp' => ['nullable', 'url', 'max:255'],
            'telegram' => ['nullable', 'url', 'max:255'],
            'price_participants' => ['nullable', 'integer', 'min:0', 'max:999999999'],
            'price_visitors' => ['nullable', 'integer', 'min:0', 'max:999999999'],
            'discount_students' => ['required', 'array', new ConferenceDiscount],
            'discount_participants' => ['required', 'array', new ConferenceDiscount],
            'discount_special_guest' => ['required', 'array', new ConferenceDiscount],
            'discount_young_scientist' => ['required', 'array', new ConferenceDiscount],
            'abstracts_price' => ['nullable', 'integer', 'min:0', 'max:999999999'],
            'abstracts_format' => ['required',  Rule::enum(AbstractsFormat::class)],
            'abstracts_lang' => ['required',  Rule::enum(AbstractsLanguage::class)],
            'max_thesis_characters' => ['required', 'int', 'min:100', 'max:20000'],
            'thesis_instruction' => ['nullable', 'string', 'max:3000'],
            'thesis_accept_until' => ['required', 'date'],
            'thesis_edit_until' => ['required', 'date', 'after_or_equal:thesis_accept_until'],
            'assets_load_until' => ['required', 'date', 'after_or_equal:thesis_edit_until'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'with_foreign_participation' => $this->boolean('with_foreign_participation'),
            'need_site' => $this->boolean('need_site'),
        ]);
    }

    public function messages(): array
    {
        return [
            'slug.regex' => __('validation.slug.regex'),
            'image.dimensions' => 'Минимальный размер изображения 200x200px',
        ];
    }
}
