@extends('layouts.app')

@section('title', __('my/participant.create.title'))

@section('content')

    <main class="page">
        <section class="event">
            <div class="event__container">
                <h1 class="event__title">{{ __('my/participant.create.h1') }}</h1>
                <form class="event__form form" @submit.prevent="submit" x-data="{
                    form: $form('post', '{{ route('participant.store') }}', {
                        name_ru: '',
                        surname_ru: '',
                        middle_name_ru: '',
                        name_en: '',
                        surname_en: '',
                        middle_name_en: '',
                        phone: '',
                        orcid_id: '',
                        website: '',
                    }),
                
                    submit() {
                        this.form.submit()
                            .then(response => {
                                location.replace(response.data.redirect ?? '/')
                            })
                            .catch(error => this.$store.toasts.handleResponseError(error));
                    },
                }">
                    @csrf

                    <div class="form__row _three">
                        <div class="form__line" :class="form.invalid('name_ru') && '_error'">
                            <label class="form__label" for="u_5">@lang('auth.register.name_ru')</label>
                            <input id="u_5" class="input" autocomplete="off" type="text" name="name_ru"
                                 placeholder="@lang('auth.register.name_ru')" x-model="form.name_ru"
                                @input.debounce.1000ms="form.validate('name_ru')">
                        </div>
                        <div class="form__line" :class="form.invalid('surname_ru') && '_error'">
                            <label class="form__label" for="u_6">@lang('auth.register.surname_ru')</label>
                            <input id="u_6" class="input" autocomplete="off" type="text" name="surname_ru"
                                 placeholder="@lang('auth.register.surname_ru')" x-model="form.surname_ru"
                                @input.debounce.1000ms="form.validate('surname_ru')">
                        </div>
                        <div class="form__line" :class="form.invalid('middle_name_ru') && '_error'">
                            <label class="form__label" for="u_7">@lang('auth.register.middle_name_ru')</label>
                            <input id="u_7" class="input" autocomplete="off" type="text" name="middle_name_ru"
                                 placeholder="@lang('auth.register.middle_name_ru')" x-model="form.middle_name_ru"
                                @input.debounce.1000ms="form.validate('middle_name_ru')">
                        </div>
                    </div>
                    <template x-if="form.invalid('name_ru')">
                        <div class="form__error" x-text="form.errors.name_ru"></div>
                    </template>
                    <template x-if="form.invalid('surname_ru')">
                        <div class="form__error" x-text="form.errors.surname_ru"></div>
                    </template>
                    <template x-if="form.invalid('middle_name_ru')">
                        <div class="form__error" x-text="form.errors.middle_name_ru"></div>
                    </template>
                    <div class="form__row _three">
                        <div class="form__line" :class="form.invalid('name_en') && '_error'">
                            <label class="form__label" for="u_8">@lang('auth.register.name_en')</label>
                            <input id="u_8" class="input" autocomplete="off" type="text" name="name_en"
                                 placeholder="@lang('auth.register.name_en')" x-model="form.name_en"
                                @input.debounce.1000ms="form.validate('name_en')">
                        </div>
                        <div class="form__line" :class="form.invalid('surname_en') && '_error'">
                            <label class="form__label" for="u_9">@lang('auth.register.surname_en')</label>
                            <input id="u_9" class="input" autocomplete="off" type="text" name="surname_en"
                                 placeholder="@lang('auth.register.surname_en')" x-model="form.surname_en"
                                @input.debounce.1000ms="form.validate('surname_en')">
                        </div>
                        <div class="form__line" :class="form.invalid('middle_name_en') && '_error'">
                            <label class="form__label" for="u_10">@lang('auth.register.middle_name_en')</label>
                            <input id="u_10" class="input" autocomplete="off" type="text" name="middle_name_en"
                                 placeholder="@lang('auth.register.middle_name_en')" x-model="form.middle_name_en"
                                @input.debounce.1000ms="form.validate('middle_name_en')">
                        </div>
                    </div>
                    <template x-if="form.invalid('name_en')">
                        <div class="form__error" x-text="form.errors.name_en"></div>
                    </template>
                    <template x-if="form.invalid('surname_en')">
                        <div class="form__error" x-text="form.errors.surname_en"></div>
                    </template>
                    <template x-if="form.invalid('middle_name_en')">
                        <div class="form__error" x-text="form.errors.middle_name_en"></div>
                    </template>
                    <div class="form__row" :class="form.invalid('phone') && '_error'">
                        <label class="form__label" for="u_4">@lang('auth.register.phone')</label>
                        <input id="u_4" class="form-block__input input" autocomplete="off" type="text"
                            name="phone"  placeholder="+7 (999) 999-99-99" x-model="form.phone"
                            @input.debounce.1000ms="form.validate('phone')">
                        <template x-if="form.invalid('phone')">
                            <div class="form__error" x-text="form.errors.phone"></div>
                        </template>
                    </div>

                    {{-- <div class="form__row">
                        <label class="form__label" for="c_3">Аффилиации (*)</label>
                        <div class="form-block">
                            <input id="c_3" class="form-block__input input" autocomplete="off" type="text"
                                name="form[]"  placeholder="Аффилиации">
                            <button class="form-block__btn _icon-plus" type="button"></button>
                        </div>
                        <div class="form__line">
                            <input class="form-block__input input" autocomplete="off" type="text" name="form[]"
                                 placeholder="Аффилиации2">
                        </div>
                        <div class="form__line">
                            <input class="form-block__input input" autocomplete="off" type="text" name="form[]"
                                 placeholder="Аффилиации3">
                        </div>
                    </div> --}}

                    <div class="form__row" :class="form.invalid('orcid_id') && '_error'">
                        <label class="form__label" for="c_4">ORCID ID</label>
                        <div class="form__line">
                            <input id="c_4" class="input" autocomplete="off" type="text" name="orcid_id"
                                 placeholder="ID" x-mask="****-****-****-****" x-model="form.orcid_id"
                            @input.debounce.1000ms="form.validate('orcid_id')">
                        </div>
						<template x-if="form.invalid('orcid_id')">
                            <div class="form__error" x-text="form.errors.orcid_id"></div>
                        </template>
                    </div>

                    <div class="form__row" :class="form.invalid('website') && '_error'">
                        <label class="form__label" for="c_5">{{ __('my/participant.create.website') }}</label>
                        <div class="form__line">
                            <input id="c_5" class="input" autocomplete="off" type="text" name="website"
                                 placeholder="http://example.ru" x-model="form.website"
                            @input.debounce.1000ms="form.validate('website')">
                        </div>
						<template x-if="form.invalid('website')">
                            <div class="form__error" x-text="form.errors.website"></div>
                        </template>
                    </div>

                    <div class="form__row">
                        <div class="form__btns">
                            <button class="form__button button button_primary" type="submit">{{ __('my/participant.create.save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
