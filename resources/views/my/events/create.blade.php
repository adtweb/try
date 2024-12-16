@extends('layouts.app')

@section('title', __('my/events/create.title'))

@section('content')
    <main class="page">
        <section class="event">
            <div class="event__container">
                <h1 class="event__title">{{ __('my/events/create.h1') }}</h1>
                <form action="#" class="event__form form" 
					@select-callback.camel.document="select"
					@submit.prevent="submit"
					x-data="{
						form: $form('post', '{{ route('conference.store') }}', {
							title_ru: '',
							title_en: '',
							slug: '',
							organization_id: '',
							conference_type_id: '',
							format: '',
							with_foreign_participation: false,
							subjects: [],
							sections: {},
							image: '',
							website: '',
							need_site: false,
							'co-organizers': {},
							address: '',
							phone: '',
							email: '',
							start_date: '',
							end_date: '',
							timezone: 'Europe/Moscow',
							description_ru: '',
							description_en: '',
							lang: '',
							participants_number: '',
							report_form: '',
							whatsapp: '',
							telegram: '',
							price_participants: '',
							price_visitors: '',
							discount_students: {amount: 0, unit: 'RUB'},
							discount_participants: {amount: 0, unit: 'RUB'},
							discount_special_guest: {amount: 0, unit: 'RUB'},
							discount_young_scientist: {amount: 0, unit: 'RUB'},
							abstracts_price: '',
							abstracts_format: '',
							abstracts_lang: '',
							max_thesis_characters: 2500,
							thesis_accept_until: '',
							thesis_edit_until: '',
							assets_load_until: '',
							thesis_instruction: '',
						}),
						formatCheckShow: true,
						formDisabled: false,
						loading: false,

						init() {
							this.$watch('form.format', (val) => this.formatCheckShow = val == 'national')
						},
						submit() {
							this.formDisabled = true
							this.loading = true

							this.form.submit()
								.then(response => {
									location.replace(route('conference.show', response.data.slug))
								})
								.catch(error => {
									this.$store.toasts.handleResponseError(error)
								})
								.finally(() => {
									this.formDisabled = false
									this.loading = false
								})
						},
					}"
				>
                    <div class="form__row" :class="form.invalid('title_ru') && '_error'">
                        <label class="form__label" for="c_1">{{ __('my/events/create.event_name_ru') }} (*)</label>
                        <input id="c_1" class="input" autocomplete="off" type="text" name="title_ru"
                            placeholder="{{ __('my/events/create.event_name_ru') }}"
							x-model="form.title_ru"	
							@input.debounce.1000ms="form.validate('title_ru')"
						>
						<template x-if="form.invalid('title_ru')">
							<div class="form__error" x-text="form.errors.title_ru"></div>
						</template>
                    </div>

                    <div class="form__row" :class="form.invalid('title_en') && '_error'">
                        <label class="form__label" for="c_2">{{ __('my/events/create.event_name_en') }} (*)</label>
                        <input id="c_2" class="input" autocomplete="off" type="text" name="title_en"
                            placeholder="{{ __('my/events/create.event_name_en') }}"
							x-model="form.title_en"	
							@input.debounce.1000ms="form.validate('title_en')"
						>
						<template x-if="form.invalid('title_en')">
							<div class="form__error" x-text="form.errors.title_en"></div>
						</template>
                    </div>

					<div class="form__row" :class="form.invalid('slug') && '_error'">
						<label class="form__label" for="a_1">{{ __('my/events/create.acronim') }} (*)</label>
						<input class="input" id="a_1" autocomplete="off" type="text" name="slug" 
							placeholder="{{ __('my/events/create.acronim_placeholder', ['year' => date('Y')]) }}"
							x-model="form.slug"	
							@input.debounce.1000ms="form.validate('slug')"
						>
						<template x-if="form.invalid('slug')">
							<div class="form__error" x-text="form.errors.slug"></div>
						</template>
						<div class="form__link" x-text="location.origin + '/events/' + form.slug"></div>
					</div>

					<div class="form__row">
						<x-form.select 
							name="organization_id"
							label="{{ __('my/events/create.organization') }} (*)" 
							option_in_form="organization_id"
						>
							@foreach ($organizations as $organization)
                            	<option value="{{ $organization->id }}">{{ $organization->{'full_name_'.loc()} }}</option>
							@endforeach
						</x-form.select>

						<button class="button tw-mt-2" type="button" @click="$dispatch('popup', 'create-organization')">
							{{ __('my/events/create.add_organization_btn') }}
						</button>
					</div>

                    <div class="form__row">
						<x-form.select 
							name="type"
							label="{{ __('my/events/create.type') }} (*)" 
							option_in_form="conference_type_id"
						>
							@foreach (conference_types() as $type)
								<option	value="{{ $type->id }}">{{ $type->{'title_'.loc()} }}</option>
							@endforeach
						</x-form.select>
                    </div>

                    <div class="form__row">
						<x-form.select 
							class="tw-mb-2"
							name="format"
							label="{{ __('my/events/create.format') }} (*)" 
							option_in_form="format"
						>
							<option value="national" selected>{{ __('my/events/create.national') }}</option>
                            <option value="international">{{ __('my/events/create.international') }}</option>
						</x-form.select>
						
                        <div class="checkbox" x-show="formatCheckShow" x-transition>
                            <input id="chx_1" class="checkbox__input" type="checkbox" value="1"
                                name="with_foreign_participation" x-model="form.with_foreign_participation">
                            <label for="chx_1" class="checkbox__label">
                                <span class="checkbox__text">{{ __('my/events/create.with_foreign_participation') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="form__row">
						<x-form.select 
							multiple
							name="subjects"
							label="{{ __('my/events/create.subjects') }} (*)" 
							option_in_form="subjects"
						>
							@foreach (subjects() as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->{'title_' . app()->getLocale()} }}</option>
                            @endforeach
						</x-form.select>
                    </div>

                    {{-- <div class="form__row" :class="form.invalid('sections') && '_error'" id="sections" 
						x-data="{
							ai: 1,

							add() {
								if (Object.keys(this.form.sections).length >= 5) return
								this.form.sections[this.ai] = {
									title_ru: '', 
									title_en: '', 
									slug: '', 
								}
								this.ai++
							},
							remove(id) {
								delete this.form.sections[id]
							},
						}"
					>
                        <label class="form__label">{{ __('my/events/create.sections') }}</label>
						<template x-for="section, id in form.sections" x-key="id">
							<div class="form-list">
								<label class="form-list__label">{{ __('my/events/create.section_name') }}</label>
								<div :class="form.invalid(`sections.${id}.slug`) && '_error'">
									<input class="input" autocomplete="off" type="text" name="slug"
										placeholder="{{ __('my/events/create.section_slug') }}" x-model="form.sections[id].slug"
										@change="form.validate(`sections.${id}.slug`)">
									<template x-if="form.invalid(`sections.${id}.slug`)">
										<div class="form__error" x-text="form.errors[`sections.${id}.slug`]"></div>
									</template>
								</div>
								<div :class="form.invalid(`sections.${id}.title_ru`) && '_error'">
									<input class="input" autocomplete="off" type="text" name="title_ru"
										placeholder="{{ __('my/events/create.section_name_ru') }}" 
										x-model="form.sections[id].title_ru" 
										@change="form.validate(`sections.${id}.title_ru`)"
									>
									<template x-if="form.invalid(`sections.${id}.title_ru`)">
										<div class="form__error" x-text="form.errors[`sections.${id}.title_ru`]"></div>
									</template>
								</div>
								<div :class="form.invalid(`sections.${id}.title_en`) && '_error'">
									<input class="input" autocomplete="off" type="text" name="title_en"
										placeholder="{{ __('my/events/create.section_name_en') }}" 
										x-model="form.sections[id].title_en"
										@change="form.validate(`sections.${id}.title_en`)"
									>
									<template x-if="form.invalid(`sections.${id}.title_en`)">
										<div class="form__error" x-text="form.errors[`sections.${id}.title_en`]"></div>
									</template>
								</div>
								<button class="button button_outline" type="button" @click="remove(id)">{{ __('my/events/create.remove_section') }}</button>
							</div>
						</template>
                        <button class="button" type="button" @click="add()">{{ __('my/events/create.add_section') }}</button>
                    </div> --}}

					<div class="form__row">
						<x-form.input-image 
							:title="__('my/events/personal.edit.logo')"
							name="image"
							:cover="false"
						/>
					</div>

                    <div class="form__row" :class="form.invalid('website') && '_error'">
                        <label class="form__label" for="c_5">{{ __('my/events/create.site') }}</label>
                        <div class="d-grid">
                            <input id="c_5" class="input" autocomplete="off" type="text" name="website"
                                placeholder="http://website.com" 
								x-model="form.website"
								@input.debounce.1000ms="form.validate('website')"
							>
                            <div class="checkbox">
                                <input id="chx_2" class="checkbox__input" type="checkbox"
                                    value="1" name="need_site" x-model="form.need_site">
                                <label for="chx_2" class="checkbox__label">
                                    <span class="checkbox__text">{{ __('my/events/create.need_site') }}</span>
                                </label>
                            </div>
                        </div>
						<template x-if="form.invalid('website')">
							<div class="form__error" x-text="form.errors.website"></div>
						</template>
                    </div>

                    <div class="form__row" :class="form.invalid('co-organizers') && '_error'" id="co-organizers"
						x-data="{
							ai: 1,
							add() {
								this.form['co-organizers'][this.ai] = ''
								this.ai++
							},
							remove(id) {
								delete this.form['co-organizers'][id]
							},
						}"
					>
                        <div class="form__line">
                            <label class="form__label" for="c_6">{{ __('my/events/create.co-organizers') }}</label>
                        </div>
                        <div class="form__line">
							<template x-for="organizer, id in form['co-organizers']">
								<div class="form-list" :class="form.invalid(`co-organizers.${id}`) && '_error'">
									<input class="input" autocomplete="off" type="text" name="form[]"
										placeholder="{{ __('my/events/create.co-organizer_name') }}" 
										x-model="form['co-organizers'][id]"
										@change="form.validate(`co-organizers.${id}`)"
									>
									<template x-if="form.invalid(`co-organizers.${id}`)">
										<div class="form__error" x-text="form.errors[`co-organizers.${id}`]"></div>
									</template>
									<button class="button button_outline" type="button" @click="remove(id)">{{ __('my/events/create.remove_co-organizer') }}</button>
								</div>
							</template>
                            <button class="button" type="button" @click="add">{{ __('my/events/create.add_co-organizer') }}</button>
                        </div>

                    </div>

                    <div class="form__row" :class="form.invalid('address') && '_error'" id="address" x-data="{
						suggestions: [],
						show: false,
					}">
                        <label class="form__label" for="c_7">{{ __('my/events/create.address') }} (*) </label>
                        <input id="c_7" class="input" autocomplete="off" type="text" name="form[]"
                            placeholder="{{ __('my/events/create.address_placeholder') }}"
							x-model="form.address"
							@input.debounce.1000ms="form.validate('address')"	
						>
                        <div class="input-tips" style="display: none" x-show="show" x-transition>
                            <ul>
                                <li>Санкт-Петербург, ул. Попова, д.5 </li>
                            </ul>
                        </div>
						<template x-if="form.invalid('address')">
							<div class="form__error" x-text="form.errors.address"></div>
						</template>
                    </div>

                    <div class="form__row" :class="form.invalid('phone') && '_error'">
                        <label class="form__label" for="c_8">{{ __('my/events/create.phone') }}</label>
                        <input id="c_8" class="input" autocomplete="off" type="text" name="form[]"
                            placeholder="{{ __('my/events/create.phone_placeholder') }}"
							x-model="form.phone"
							@input.debounce.1000ms="form.validate('phone')"	
						>
						<template x-if="form.invalid('phone')">
							<div class="form__error" x-text="form.errors.phone"></div>
						</template>
                    </div>

                    <div class="form__row" :class="form.invalid('email') && '_error'">
                        <label class="form__label" for="c_9">{{ __('my/events/create.email') }} (*)</label>
                        <input id="c_9" class="input" autocomplete="off" type="text" name="form[]"
                            placeholder="mail@mail.ru"
							x-model="form.email"
							@input.debounce.1000ms="form.validate('email')"
						>
						<template x-if="form.invalid('email')">
							<div class="form__error" x-text="form.errors.email"></div>
						</template>
                    </div>

                    <div class="form__row _two">
                        <div class="form__line" :class="form.invalid('start_date') && '_error'">
                            <label class="form__label" for="date-start">{{ __('my/events/create.start_date') }} (*)</label>
                            <input id="date-start" class="input" autocomplete="off" type="date" name="form[]"
                                placeholder="__.__.____"
								x-model="form.start_date"
								@change="form.validate('start_date')"
							>
                            <template x-if="form.invalid('start_date')">
								<div class="form__error" x-text="form.errors.start_date"></div>
							</template>
                        </div>
                        <div class="form__line" :class="form.invalid('end_date') && '_error'">
                            <label class="form__label" for="date-start">{{ __('my/events/create.end_date') }} (*)</label>
                            <input id="date-end" class="input" autocomplete="off" type="date"
                                placeholder="__.__.____"
								x-model="form.end_date"
								@change="form.validate('end_date')"
							>
							<template x-if="form.invalid('end_date')">
								<div class="form__error" x-text="form.errors.end_date"></div>
							</template>
                        </div>
                    </div>

					<div class="form__row">
						<x-form.select 
							name="timezone"
							label="{{ __('my/events/create.timezone') }} (*)" 
							option_in_form="timezone"
						>
							@foreach (App\Enums\Timezone::all() as $timezone => $label)
                            	<option value="{{ $timezone }}" @if($timezone == 'Europe/Moscow') selected @endif>{{ $label }}</option>
							@endforeach
						</x-form.select>
					</div>

                    <div class="form__row" :class="form.invalid('description_ru') && '_error'">
                        <label class="form__label" for="t_1">{{ __('my/events/create.description_ru') }} (*)</label>
                        <textarea id="t_1" autocomplete="off" name="description_ru" placeholder="{{ __('my/events/create.description_ru_placeholder') }}"
                            class="input _small"
							x-model="form.description_ru"
							@change="form.validate('description_ru')"
						></textarea>
						<template x-if="form.invalid('description_ru')">
							<div class="form__error" x-text="form.errors.description_ru"></div>
						</template>
                    </div>

                    <div class="form__row" :class="form.invalid('description_en') && '_error'">
                        <label class="form__label" for="t_1">{{ __('my/events/create.description_en') }} (*)</label>
                        <textarea id="t_1" autocomplete="off" name="description_en" placeholder="{{ __('my/events/create.description_en_placeholder') }}"
                            class="input _small"
							x-model="form.description_en"
							@change="form.validate('description_en')"
						></textarea>
						<template x-if="form.invalid('description_en')">
							<div class="form__error" x-text="form.errors.description_en"></div>
						</template>
                    </div>

                    <div class="form__row">
						<x-form.select 
							name="lang"
							label="{{ __('my/events/create.lang') }} (*)" 
							option_in_form="lang"
						>
							<option value="ru" selected>{{ __('my/events/create.lang_ru') }}</option>
                            <option value="en">{{ __('my/events/create.lang_en') }}</option>
                            <option value="mixed">{{ __('my/events/create.lang_mixed') }}</option>
                            <option value="other">{{ __('my/events/create.lang_other') }}</option>
						</x-form.select>
                    </div>

                    <div class="form__row">
						<x-form.select 
							name="participants_number"
							label="{{ __('my/events/create.participants_number') }}" 
							option_in_form="participants_number"
						>
							@foreach (Src\Domains\Conferences\Enums\ParticipantsNumber::cases() as $number)
                            	<option value="{{ $number->value }}">{{ $number->toString() }}</option>
							@endforeach
						</x-form.select>
                    </div>

                    <div class="form__row">
						<x-form.select 
							name="report_form"
							label="{{ __('my/events/create.report_form') }} (*)" 
							option_in_form="report_form"
						>
							@foreach (Src\Domains\Conferences\Enums\ConferenceReportForm::cases() as $reportForm)
                            	<option value="{{ $reportForm->value }}">{{ $reportForm->toString() }}</option>
							@endforeach
						</x-form.select>
                    </div>

                    <div class="form__row" :class="form.invalid('whatsapp') && '_error'">
                        <label class="form__label" for="c_10">{{ __('my/events/create.whatsapp') }}</label>
                        <input id="c_10" class="input" autocomplete="off" type="text" name="form[]"
                            placeholder="https://wa.me/791200000000"
							x-model="form.whatsapp"
							@change="form.validate('whatsapp')"
						>
						<template x-if="form.invalid('whatsapp')">
							<div class="form__error" x-text="form.errors.whatsapp"></div>
						</template>
                    </div>

                    <div class="form__row" :class="form.invalid('telegram') && '_error'">
                        <label class="form__label" for="c_11">{{ __('my/events/create.telegram') }}</label>
                        <input id="c_11" class="input" autocomplete="off" type="text" name="form[]"
                            placeholder="https://t.me/yournickname"
							x-model="form.telegram"
							@change="form.validate('telegram')"
						>
						<template x-if="form.invalid('telegram')">
							<div class="form__error" x-text="form.errors.telegram"></div>
						</template>
                    </div>

					<div class="form__row" :class="form.invalid('price_participants') && '_error'" x-data="{
						show: false,
						change() {
							if (this.show === false) {
								this.form.price_participants = ''
							}
						},
					}">
                        <label class="form__label">{{ __('my/events/create.price_participants') }}</label>
                        <div class="checkbox">
                            <input id="chx_11" class="checkbox__input" type="checkbox"
                                value="1" name="price_participants_check" @change="change" x-model="show">
                            <label for="chx_11" class="checkbox__label">
                                <span class="checkbox__text">Есть</span>
                            </label>
                        </div>
                        <input class="input" autocomplete="off" type="text" name="form[]"
                            placeholder="Сумма оплаты" 
							x-show="show"
							x-transition
							x-model="form.price_participants"
							@change="form.validate('price_participants')"
						>
						<template x-if="form.invalid('price_participants') && show">
							<div class="form__error" x-text="form.errors.price_participants"></div>
						</template>
                    </div>

					<div class="form__row" :class="form.invalid('price_visitors') && '_error'" x-data="{
						show: false,
						change() {
							if (this.show === false) {
								this.form.price_visitors = ''
							}
						},
					}">
                        <label class="form__label">{{ __('my/events/create.price_visitors') }}</label>
                        <div class="checkbox">
                            <input id="chx_12" class="checkbox__input" type="checkbox"
                                value="1" name="price_visitors_check" @change="change" x-model="show">
                            <label for="chx_12" class="checkbox__label">
                                <span class="checkbox__text">Есть</span>
                            </label>
                        </div>
                        <input class="input" autocomplete="off" type="text" name="price_visitors"
                            placeholder="Сумма оплаты" 
							x-show="show"
							x-transition
							x-model="form.price_visitors"
							@change="form.validate('price_visitors')"
						>
						<template x-if="form.invalid('price_visitors') && show">
							<div class="form__error" x-text="form.errors.price_visitors"></div>
						</template>
                    </div>

					<div class="form__row" :class="form.invalid('abstracts_price') && '_error'" x-data="{
						show: false,
						change() {
							if (this.show === false) {
								this.form.abstracts_price = ''
							}
						},
					}">
                        <label class="form__label">{{ __('my/events/create.abstracts_price') }}</label>
                        <div class="checkbox">
                            <input id="chx_13" class="checkbox__input" type="checkbox"
                                value="1" name="abstracts_price_check" @change="change" x-model="show">
                            <label for="chx_13" class="checkbox__label">
                                <span class="checkbox__text">Есть</span>
                            </label>
                        </div>
                         <input class="input" autocomplete="off" type="text"
                            placeholder="Сумма оплаты" 
							x-show="show"
							x-transition
							x-model="form.abstracts_price"
							@change="form.validate('abstracts_price')"
						>
						<template x-if="form.invalid('abstracts_price') && show">
							<div class="form__error" x-text="form.errors.abstracts_price"></div>
						</template>
                    </div>

                    <div class="form__row">
						<label class="form__label">{{ __('my/events/create.discounts') }}</label>
						<div class="checkbox-block" 
							:class="form.invalid('discount_students') && '_error'"
							x-data="{
								show: false,

								change() {
									if (this.$el.checked) return
									this.form.discount_students.amount = ''
								}
							}"
						>
							<div class="checkbox">
								<input id="chx_3" class="checkbox__input" type="checkbox" x-model="show" @change="change">
								<label for="chx_3" class="checkbox__label">
									<span class="checkbox__text">{{ __('my/events/create.discount_students') }}</span>
								</label>
							</div>
							<div class="checkbox-block__input" x-show="show" x-transition>
								<div class="form__line">
									<input class="input" autocomplete="off" type="text" name="form[]" placeholder="Размер скидки"
										x-model="form.discount_students.amount"
										@change="form.validate('discount_students')"
									>
								</div>
								<div class="form__line">
									<x-form.select 
										name="discount_students_unit"
										option_in_form="discount_students_unit"
									>
										<option value="RUB" selected>В рублях</option>
										<option value="percent">В %</option>
									</x-form.select>
								</div>
							</div>
							<template x-if="form.invalid('discount_students') && show">
								<div class="form__error" x-text="form.errors.discount_students"></div>
							</template>
						</div>
						<div class="checkbox-block"
							:class="form.invalid('discount_participants') && '_error'"
							x-data="{
								show: false,

								change() {
									if (this.$el.checked) return
									this.form.discount_participants.amount = ''
								}
							}"
						>
							<div class="checkbox">
								<input id="chx_4" checked class="checkbox__input" type="checkbox" x-model="show" @change="change">
								<label for="chx_4" class="checkbox__label">
									<span class="checkbox__text">{{ __('my/events/create.discount_participants') }}</span>
								</label>
							</div>
							<div class="checkbox-block__input" x-show="show" x-transition>
								<div class="form__line">
									<input class="input" autocomplete="off" type="text" placeholder="Размер скидки"
										x-model="form.discount_participants.amount"
										@change="form.validate('discount_participants')"
									>
								</div>
								<div class="form__line">
									<x-form.select 
										name="discount_participants_unit"
										option_in_form="discount_participants_unit"
									>
										<option value="RUB" selected>В рублях</option>
										<option value="percent">В %</option>
									</x-form.select>
								</div>
							</div>
							<template x-if="form.invalid('discount_participants') && show">
								<div class="form__error" x-text="form.errors.discount_participants"></div>
							</template>
						</div>
						<div class="checkbox-block"
							:class="form.invalid('discount_special_guest') && '_error'"
							x-data="{
								show: false,

								change() {
									if (this.$el.checked) return
									this.form.discount_special_guest.amount = ''
								}
							}"
						>
							<div class="checkbox">
								<input id="chx_5" class="checkbox__input" type="checkbox" x-model="show" @change="change">
								<label for="chx_5" class="checkbox__label">
									<span class="checkbox__text">{{ __('my/events/create.special_guest') }}</span>
								</label>
							</div>
							<div class="checkbox-block__input" x-show="show" x-transition>
								<div class="form__line">
									<input class="input" autocomplete="off" type="text" placeholder="Размер скидки"
										x-model="form.discount_special_guest.amount"
										@change="form.validate('discount_special_guest')"
									>
								</div>
								<div class="form__line">
									<x-form.select 
										name="discount_special_guest_unit"
										option_in_form="discount_special_guest_unit"
									>
										<option value="RUB" selected>В рублях</option>
										<option value="percent">В %</option>
									</x-form.select>
								</div>
							</div>
							<template x-if="form.invalid('discount_special_guest') && show">
								<div class="form__error" x-text="form.errors.discount_special_guest"></div>
							</template>
						</div>
						<div class="checkbox-block"
							:class="form.invalid('discount_young_scientist') && '_error'"
							x-data="{
								show: false,

								change() {
									if (this.$el.checked) return
									this.form.discount_young_scientist.amount = ''
								}
							}"
						>
							<div class="checkbox">
								<input id="chx_6" checked class="checkbox__input" type="checkbox" x-model="show" @change="change">
								<label for="chx_6" class="checkbox__label">
									<span class="checkbox__text">{{ trans('my/events/create.young_scientist') }}</span>
								</label>
							</div>
							<div class="checkbox-block__input" x-show="show" x-transition>
								<div class="form__line">
									<input class="input" autocomplete="off" type="text" placeholder="Размер скидки"
										x-model="form.discount_young_scientist.amount"
										@change="form.validate('discount_young_scientist')"
									>
								</div>
								<div class="form__line">
									<x-form.select 
										name="discount_young_scientist_unit"
										option_in_form="discount_young_scientist_unit"
									>
										<option value="RUB" selected>В рублях</option>
										<option value="percent">В %</option>
									</x-form.select>
								</div>
							</div>
							<template x-if="form.invalid('discount_young_scientist') && show">
								<div class="form__error" x-text="form.errors.discount_young_scientist"></div>
							</template>
						</div>
					</div>


                    <div class="form__row">
						<x-form.select 
							name="abstracts_format"
							label="{{ __('my/events/create.abstracts_format') }} (*)" 
							option_in_form="abstracts_format"
						>
							<option value="A4" selected>А4</option>
                            <option value="A5">А5</option>
						</x-form.select>
                    </div>

                    <div class="form__row">
						<x-form.select 
							name="abstracts_lang"
							label="{{ __('my/events/create.abstracts_lang') }} (*)" 
							option_in_form="abstracts_lang"
						>
							<option value="ru" selected>{{ __('my/events/create.abstracts_lang_ru') }}</option>
                            <option value="en">{{ __('my/events/create.abstracts_lang_en') }}</option>
						</x-form.select>
                    </div>

					<div class="form__row" :class="form.invalid('max_thesis_characters') && '_error'">
                        <label class="form__label" for="c_21">{{ __('my/events/create.max_thesis_characters') }} (*)</label>
                        <input id="c_21" class="input" autocomplete="off" type="text" name="max_thesis_characters"
                            placeholder="{{ __('my/events/create.max_thesis_characters') }}"
							x-model="form.max_thesis_characters"	
							@input.debounce.1000ms="form.validate('max_thesis_characters')"
						>
						<template x-if="form.invalid('max_thesis_characters')">
							<div class="form__error" x-text="form.errors.max_thesis_characters"></div>
						</template>
                    </div>

					<div class="form__row _three">
                        <div class="form__line tw-flex tw-flex-col" :class="form.invalid('thesis_accept_until') && '_error'">
                            <label class="form__label" for="thesis_accept_until">{{ __('my/events/create.thesis_accept_until') }} (*)</label>
                            <input id="thesis_accept_until" class="input tw-mt-auto" autocomplete="off" type="date"
                                placeholder="__.__.____"
								x-model="form.thesis_accept_until"
								@change="form.validate('thesis_accept_until')"
							>
                            <template x-if="form.invalid('thesis_accept_until')">
								<div class="form__error" x-text="form.errors.thesis_accept_until"></div>
							</template>
                        </div>
                        <div class="form__line tw-flex tw-flex-col" :class="form.invalid('thesis_edit_until') && '_error'">
                            <label class="form__label" for="thesis_edit_until">{{ __('my/events/create.thesis_edit_until') }} (*)</label>
                            <input id="thesis_edit_until" class="input tw-mt-auto" autocomplete="off" type="date"
                                placeholder="__.__.____"
								x-model="form.thesis_edit_until"
								@change="form.validate('thesis_edit_until')"
							>
							<template x-if="form.invalid('thesis_edit_until')">
								<div class="form__error" x-text="form.errors.thesis_edit_until"></div>
							</template>
                        </div>
						<div class="form__line tw-flex tw-flex-col" :class="form.invalid('assets_load_until') && '_error'">
							<label class="form__label" for="assets_load_until">{{ __('my/events/create.assets_load_until') }} (*)</label>
							<input id="assets_load_until" class="input tw-mt-auto" autocomplete="off" type="date" placeholder="__.__.____"
								x-model="form.assets_load_until" @change="form.validate('assets_load_until')">
							<template x-if="form.invalid('assets_load_until')">
								<div class="form__error" x-text="form.errors.assets_load_until"></div>
							</template>
						</div>
                    </div>

					<div class="form__row" :class="form.invalid('thesis_instruction') && '_error'">
                        <label class="form__label" for="t_1">{{ __('my/events/create.thesis_instruction') }}</label>
                        <textarea id="t_1" autocomplete="off" name="thesis_instruction" placeholder="{{ __('my/events/create.thesis_instruction') }}"
                            class="input"
							style="height: 300px"
							x-model="form.thesis_instruction"
							@change="form.validate('thesis_instruction')"
						></textarea>
						<template x-if="form.invalid('thesis_instruction')">
							<div class="form__error" x-text="form.errors.thesis_instruction"></div>
						</template>
                    </div>

                    <div class="form__row">
                        <button class="form__button button button_primary" type="submit"
							:disabled="form.processing || formDisabled"
						>
							{{ __('my/events/create.btn') }}
							<x-loader class="tw-w-5 tw-h-4"/>
						</button>
                    </div>
                    <div class="form__row">
                        <template x-if="form.hasErrors">
							<div class="form__error">{{ __('my/events/create.errors') }}</div>
						</template>
                    </div>
                </form>
            </div>
        </section>

		@include('partials.modals.create-organization')
    </main>
@endsection
