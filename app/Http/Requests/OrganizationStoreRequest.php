<?php

namespace App\Http\Requests;

use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;

class OrganizationStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'full_name_ru' => ['required', 'string', 'max:255', 'regex:~^[^a-z]+$~u'],
            'short_name_ru' => ['nullable', 'string', 'max:255', 'regex:~^[^a-z]+$~u'],
            'full_name_en' => ['required', 'string', 'max:255', 'regex:~^[^а-яА-Я]+$~u'],
            'short_name_en' => ['nullable', 'string', 'max:255', 'regex:~^[^а-яА-Я]+$~u'],
            'inn' => ['nullable', 'numeric', 'digits_between:10,12'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', new Phone],
            'whatsapp' => ['nullable', 'string', 'max:255', 'url'],
            'telegram' => ['nullable', 'string', 'max:255', 'url'],
            'type' => ['required', 'string', 'max:255'],
            'actions' => ['required', 'array'],
            'actions.*' => ['required', 'string', 'max:150'],
            'vk' => ['nullable', 'string', 'max:255', 'url'],
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name_ru' => __('validation.attributes.full_name'),
            'short_name_ru' => __('validation.attributes.short_name'),
            'full_name_en' => __('validation.attributes.full_name'),
            'short_name_en' => __('validation.attributes.short_name'),
            'inn' => __('validation.attributes.inn'),
            'address' => __('validation.attributes.address'),
            'phone' => __('validation.attributes.phone'),
            'whatsapp' => __('validation.attributes.whatsapp'),
            'telegram' => __('validation.attributes.telegram'),
            'type' => __('validation.attributes.type'),
            'actions' => __('validation.attributes.actions'),
            'actions.*' => __('validation.attributes.actions'),
            'vk' => __('validation.attributes.vk'),
            'logo' => __('validation.attributes.logo'),
        ];
    }
}
