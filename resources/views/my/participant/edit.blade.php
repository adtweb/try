@extends('layouts.app')

@section('title', __('my/participant.edit.title'))

@section('content')
	<script>
		let affiliations = @json(auth()->user()->participant->affiliations ?? []);
		if (affiliations.length === 0) affiliations = {} 
	</script>
    <main class="page">
        <section class="event">
            <div class="event__container">
                <h1 class="event__title">{{ __('my/participant.edit.h1') }}</h1>
                <form class="event__form form" @submit.prevent="submit" enctype="multipart/form-data" x-data="{
                    form: $form('post', '{{ route('participant.update') }}', {
                        name_ru: '{{ auth()->user()->participant->name_ru }}',
                        surname_ru: '{{ auth()->user()->participant->surname_ru }}',
                        middle_name_ru: '{{ auth()->user()->participant->middle_name_ru }}',
                        name_en: '{{ auth()->user()->participant->name_en }}',
                        surname_en: '{{ auth()->user()->participant->surname_en }}',
                        middle_name_en: '{{ auth()->user()->participant->middle_name_en }}',
                        phone: '{{ auth()->user()->participant->phone?->raw() }}',
						image: null,
						affiliations: affiliations,
                        orcid_id: '{{ auth()->user()->participant->orcid_id }}',
                        website: '{{ auth()->user()->participant->website }}',
                    }),
                
                    submit() {
						this.copyEnglishAffiliationName()

                        this.form.submit()
                            .then(response => {
                                location.replace(response.data.redirect ?? '/')
                            })
                            .catch(error => this.$store.toasts.handleResponseError(error));
                    },
					affiliationsIds() {
						let result = []
						Object.values(this.form.affiliations)
							.forEach(el => {
								if (el.id == '') return
								result.push(el.id)
							})
						return result
					},
					copyEnglishAffiliationName() {
						Object.keys(this.form.affiliations)
							.forEach(key => {
								if (this.form.affiliations[key].no_affiliation && this.form.affiliations[key].title_ru === '') {
									this.form.affiliations[key].title_ru = this.form.affiliations[key].title_en
								}
							})
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
                            name="phone" placeholder="+7 (999) 999-99-99" x-model="form.phone"
                            @input.debounce.1000ms="form.validate('phone')">
                        <template x-if="form.invalid('phone')">
                            <div class="form__error" x-text="form.errors.phone"></div>
                        </template>
                    </div>

					<div class="form__row">
						<x-form.input-image 
							:image="auth()->user()->participant->photo"
							name="image"
							:delete_url="route('participant.avatar.delete')"
						/>
                    </div>

                    <div class="form__row" id="affiliations" x-data="{
						ai: Object.keys(affiliations).length > 0 ? +Object.keys(affiliations).pop() + 1 : 1,

						add() {
							if (Object.keys(this.form.affiliations).length >= 5) return
							this.form.affiliations[this.ai] = {
								id: '',
								title_ru: '', 
								title_en: '', 
								country: {},
								has_mistake: false,
								no_affiliation: false,
							}
							this.ai++
						},
						remove(id) {
							delete this.form.affiliations[id]
						},
					}">
                        <label class="form__label" for="f_1">{{ __('my/participant.edit.affiliations') }}</label>
						<template x-for="affiliation, id in form.affiliations" x-key="id">
							<div class="affiliation form__line" x-data="{
								suggestions: [],
								countries: [],
								show: false,
								showCountries: false,
								hasMistake: affiliation.has_mistake,
								noAffiliation: affiliation.no_affiliation,
	
								getSuggestions() {
									if (this.noAffiliation || this.hasMistake) return
									if (this.$el.value.trim() === '') return
	
									axios
										.get('{{ route('affiliations.index') }}', {
											params: {
												search: this.$el.value,
												except: this.affiliationsIds()
											}
										})
										.then(resp => {
											this.suggestions = resp.data
											this.show = true
										})
								},
								getCountries() {
									if (this.$el.value.trim() === '') return

									axios
										.get('{{ route('countries.index') }}', {
											params: {
												search: this.$el.value,
											}
										})
										.then(resp => {
											this.countries = resp.data
											this.showCountries = true
										})
								},
								select(suggestion, id) {
									this.form.affiliations[id].id = suggestion.id
									this.form.affiliations[id].title_ru = suggestion.title_ru
									this.form.affiliations[id].title_en = suggestion.title_en
									this.show = false
								},
								selectCountry(country, id) {
									this.form.affiliations[id].country= country
									this.showCountries = false
								},
								changeMistake() {
									if (this.$el.checked) {
										this.noAffiliation = false
									} else {
										this.form.affiliations[id].id = ''
										this.form.affiliations[id].title_ru = ''
										this.form.affiliations[id].title_en = ''
									}
									this.form.affiliations[id].has_mistake = this.$el.checked
									this.form.affiliations[id].country = {}
								},
								changeNoAffiliation() {
									this.form.affiliations[id].id = ''
									this.form.affiliations[id].title_ru = ''
									this.form.affiliations[id].title_en = ''
									this.form.affiliations[id].no_affiliation = this.$el.checked

									if (this.$el.checked) {
										this.hasMistake = false
										this.form.affiliations[id].country.id = ''
									} else {
										this.form.affiliations[id].country = {}
									}
								},
								placeholderRu() {
									if (this.noAffiliation) {
										return `{{ __('my/participant.edit.placeholders.no_affiliation_ru') }}`
									}

									return `{{ __('my/participant.edit.placeholders.affiliation_ru') }}`
								},
								placeholderEn() {
									if (this.noAffiliation) {
										return `{{ __('my/participant.edit.placeholders.no_affiliation_en') }}`
									}

									return `{{ __('my/participant.edit.placeholders.affiliation_en') }}`
								},
							}">
								<div class="form__line" @click.outside="show = false">
									<textarea autocomplete="off"
										:placeholder="placeholderRu" 
										class="input"
										:class="form.invalid(`affiliations.${id}.title_ru`) && '_error'"
										x-model="form.affiliations[id].title_ru" 
										@input.debounce.500ms="getSuggestions"	
									></textarea>
									<template x-if="form.invalid(`affiliations.${id}.title_ru`)">
										<div class="form__error" x-text="form.errors[`affiliations.${id}.title_ru`]"></div>
									</template>
									<div class="input-tips" x-show="show" x-transition.opacity>
										<ul>
											<template x-for="suggestion in suggestions">
												<li x-text="suggestion.title_ru" @click="select(suggestion, id)"></li>
											</template>
											<template x-if="suggestions.length === 0">
												<li>{{ __('my/participant.edit.no_suggestions') }}</li>
											</template>
										</ul>
									</div>
	
								</div>
								<div class="form__line">
									<textarea autocomplete="off" :placeholder="placeholderEn"  class="input"
										:class="form.invalid(`affiliations.${id}.title_en`) && '_error'"
										:disabled="!hasMistake && !noAffiliation"
										x-model="form.affiliations[id].title_en" 
									></textarea>
									<template x-if="form.invalid(`affiliations.${id}.title_en`)">
										<div class="form__error" x-text="form.errors[`affiliations.${id}.title_en`]"></div>
									</template>
									<template x-if="form.invalid(`affiliations.${id}`)">
										<div class="form__error" x-text="form.errors[`affiliations.${id}`]"></div>
									</template>
								</div>

								<div class="form__line" 
									x-show="noAffiliation" 
									:class="form.invalid('country.id') && '_error'" 
									@click.outside="showCountries = false"
								>
									<input class="form-block__input input" autocomplete="off" type="text"
										placeholder="Please start typing the country"
										:value="affiliation.country?.name_ru ? affiliation.country?.name_ru + ' | ' + affiliation.country?.name_en : ''"
										@input.debounce.500ms="getCountries">
									<template x-if="form.invalid(`affiliations.${id}.country.id`)">
										<div class="form__error" x-text="form.errors[`affiliations.${id}.country.id`]"></div>
									</template>
									<div class="input-tips" x-show="showCountries" x-transition.opacity>
										<ul>
											<template x-for="country in countries">
												<li x-text="country.name_ru + `| ${country.name_en}`" @click="selectCountry(country, id)"></li>
											</template>
											<template x-if="countries.length === 0">
												<li>{{ __('my/participant.edit.no_suggestions') }}</li>
											</template>
										</ul>
									</div>
								</div>

								<div class="form__line">
									<div class="checkbox-items">
										<div class="checkbox">
											<input :id="'a_1' + id" class="checkbox__input" type="checkbox" :name="'handle' + id"
												x-model="hasMistake" @change="changeMistake">
											<label :for="'a_1' + id" class="checkbox__label">
												<span class="checkbox__text">{{ __('my/participant.edit.affiliation_mistake') }}</span>
											</label>
										</div>
										<div class="checkbox">
											<input :id="'a_2' + id" class="checkbox__input" type="checkbox" :name="'handle' + id"
												x-model="noAffiliation" @change="changeNoAffiliation">
											<label :for="'a_2' + id" class="checkbox__label">
												<span class="checkbox__text">{{ __('my/participant.edit.no_affiliation') }}</span>
											</label>
										</div>
									</div>
								</div>
								<div class="form__line">
									<button class="form__button button button_outline" type="button" @click="remove(id)">
										{{ __('my/participant.edit.remove_affiliation') }}
									</button>
								</div>
							</div>
						</template>

						<div class="form__line">
							<button class="form__button button" type="button" @click="add">{{ __('my/participant.edit.add_affiliation') }}</button>
						</div>
                    </div>

                    <div class="form__row" :class="form.invalid('orcid_id') && '_error'">
                        <label class="form__label" for="c_4">ORCID ID</label>
                        <div class="form__line">
                            <input id="c_4" class="input" autocomplete="off" type="text" name="orcid_id"
                                placeholder="ID" x-mask="****-****-****-****"
                                x-model="form.orcid_id" @input.debounce.1000ms="form.validate('orcid_id')">
                        </div>
                        <template x-if="form.invalid('orcid_id')">
                            <div class="form__error" x-text="form.errors.orcid_id"></div>
                        </template>
                    </div>

                    <div class="form__row" :class="form.invalid('website') && '_error'">
                        <label class="form__label" for="c_5">{{ __('my/participant.edit.website') }}</label>
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
                            <button class="form__button button button_primary" type="submit">{{ __('my/participant.edit.save') }}</button>
                            <a href="{{ route('my.password.edit') }}" class="form__button button">{{ __('my/participant.edit.change-password') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
