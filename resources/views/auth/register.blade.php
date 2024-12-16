@extends('layouts.auth')

@section('title', __('auth.register.title'))

@section('h1', __('auth.register.auth'))

@section('content')
    {{-- <h2 class="registration__subtitle">@lang('auth.register.message')</h2> --}}
    <div class="tabs">
        {{-- <nav data-tabs-titles class="tabs__navigation">
            <button type="button" class="tabs__title _tab-active">@lang('auth.register.participant')</button>
            <button type="button" class="tabs__title">@lang('auth.register.organization')</button>
        </nav> --}}
        <div data-tabs-body class="tabs__content">
            <div class="tabs__body">
                <div class="tabs__body-title">@lang('auth.register.participant')</div>
                <form action="{{ route('register.participant') }}" method="POST" id="participant_form" class="registration__form form"
					@submit.prevent="submit"
					x-data="{
						form: $form('post', '{{ route('register.participant') }}', {
							email: '',
							password: '',
							password_confirmation: '',
							name_ru: '',
							surname_ru: '',
							middle_name_ru: '',
							name_en: '',
							surname_en: '',
							middle_name_en: '',
							phone: '',
						}),
						loading: false,

						submit() {
							this.loading = true
							this.form.submit()
								.then(response => {
									location.replace('/')
								})
								.catch(error => {
									this.$store.toasts.handleResponseError(error);
								})
								.finally(() => {
									this.loading = false
								})
						},
					}"
				>
					@csrf
                    <div class="form__row" :class="form.invalid('email') && '_error'">
                        <label class="form__label" for="u_1">@lang('auth.register.email') (*)</label>
                        <input id="u_1" class="form-block__input input" autocomplete="off" type="email"
                            name="email" placeholder="@lang('auth.register.email')"
							x-model="form.email"
							@input.debounce.1000ms="form.validate('email')"
						>
						<template x-if="form.invalid('email')">
							<div class="form__error" x-text="form.errors.email"></div>
						</template>
                    </div>
                    <div class="form__row" :class="form.invalid('password') && '_error'">
                        <label class="form__label" for="u_2">@lang('auth.register.password') (*)</label>
                        <div class="form-block">
                            <input id="u_2" class="form-block__input input" autocomplete="off" type="password"
                                name="password" placeholder="@lang('auth.register.password')"
								x-model="form.password"
								@input.debounce.1000ms="form.validate('password')"
							>
                            <button class="form-block__btn btn__viewpass _icon-eye" type="button" tabindex="-1"></button>
                        </div>
						<template x-if="form.invalid('password')">
							<div class="form__error" x-text="form.errors.password"></div>
						</template>
                    </div>
                    <div class="form__row" :class="form.invalid('repeat') && '_error'">
                        <label class="form__label" for="u_3">@lang('auth.register.repeat') (*)</label>
                        <div class="form-block">
                            <input id="u_3" class="form-block__input input" autocomplete="off" type="password"
                                name="password_confirmation" placeholder="@lang('auth.register.repeat')"
								x-model="form.password_confirmation"
								@input.debounce.1000ms="form.validate('password_confirmation')"
							>
                            <button class="form-block__btn btn__viewpass _icon-eye" type="button" tabindex="-1"></button>
                        </div>
						<template x-if="form.invalid('password_confirmation')">
							<div class="form__error" x-text="form.errors.password_confirmation"></div>
						</template>
                    </div>
                    <div class="form__row _three">
                        <div class="form__line" :class="form.invalid('name_ru') && '_error'">
                            <label class="form__label" for="u_5">@lang('auth.register.name_ru')</label>
                            <input id="u_5" class="input" autocomplete="off" type="text" name="name_ru"
                                placeholder="@lang('auth.register.name_ru')" 
								x-model="form.name_ru"
								@input.debounce.1000ms="form.validate('name_ru')"
							>
                        </div>
                        <div class="form__line" :class="form.invalid('surname_ru') && '_error'">
                            <label class="form__label" for="u_6">@lang('auth.register.surname_ru')</label>
                            <input id="u_6" class="input" autocomplete="off" type="text" name="surname_ru"
                                placeholder="@lang('auth.register.surname_ru')"
								x-model="form.surname_ru"
								@input.debounce.1000ms="form.validate('surname_ru')"
							>
                        </div>
                        <div class="form__line" :class="form.invalid('middle_name_ru') && '_error'">
                            <label class="form__label" for="u_7">@lang('auth.register.middle_name_ru')</label>
                            <input id="u_7" class="input" autocomplete="off" type="text" name="middle_name_ru"
                                placeholder="@lang('auth.register.middle_name_ru')"
								x-model="form.middle_name_ru"
								@input.debounce.1000ms="form.validate('middle_name_ru')"
							>
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
                                placeholder="@lang('auth.register.name_en')"
								x-model="form.name_en"
								@input.debounce.1000ms="form.validate('name_en')"
							>
                        </div>
                        <div class="form__line" :class="form.invalid('surname_en') && '_error'">
                            <label class="form__label" for="u_9">@lang('auth.register.surname_en')</label>
                            <input id="u_9" class="input" autocomplete="off" type="text" name="surname_en"
                                placeholder="@lang('auth.register.surname_en')"
								x-model="form.surname_en"
								@input.debounce.1000ms="form.validate('surname_en')"
							>
                        </div>
                        <div class="form__line" :class="form.invalid('middle_name_en') && '_error'">
                            <label class="form__label" for="u_10">@lang('auth.register.middle_name_en')</label>
                            <input id="u_10" class="input" autocomplete="off" type="text" name="middle_name_en"
                                placeholder="@lang('auth.register.middle_name_en')"
								x-model="form.middle_name_en"
								@input.debounce.1000ms="form.validate('middle_name_en')"
							>
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
                            name="phone" placeholder="+7 (999) 999-99-99"
							x-model="form.phone"
							@input.debounce.1000ms="form.validate('phone')"
						>
						<template x-if="form.invalid('phone')">
							<div class="form__error" x-text="form.errors.phone"></div>
						</template>
                    </div>
                    <div class="form__row">
                        <button class="form__button button button_primary" :disabled="form.processing" type="submit">
							@lang('auth.register.send')
							<x-loader class="tw-w-5 tw-h-4"/>
						</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
