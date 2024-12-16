@extends('layouts.auth')

@section('title', __('my/events/participation.participate.title'))

@section('h1', __('my/events/participation.participate.h1'))

@section('back_link', route('conference.show', $conference->slug))

@section('content')
	<script>
		let affiliations = @json(auth()->user()->participant->affiliations ?? []);
		if (affiliations.length === 0) affiliations = {} 
	</script>

    <form class="registration__form form" @submit.prevent="submit" x-data="{
        form: $form('post', '{{ route('participation.store', $conference->slug) }}', {
            conference_id: '{{ $conference->id }}',
            participant_id: '{{ auth()->user()->participant->id }}',
            name_ru: '{{ auth()->user()->participant->name_ru }}',
            surname_ru: '{{ auth()->user()->participant->surname_ru }}',
            middle_name_ru: '{{ auth()->user()->participant->middle_name_ru }}',
            name_en: '{{ auth()->user()->participant->name_en }}',
            surname_en: '{{ auth()->user()->participant->surname_en }}',
            middle_name_en: '{{ auth()->user()->participant->middle_name_en }}',
            phone: '{{ auth()->user()->participant->phone?->raw() }}',
			affiliations: affiliations,
            email: '{{ auth()->user()->email }}',
            orcid_id: '{{ auth()->user()->participant->orcid_id }}',
            website: '{{ auth()->user()->participant->website }}',
			participation_type: '',
			is_young: false,
        }),
		loading: false,
    
        submit() {
			this.copyEnglishAffiliationName()

			this.loading = true
			
            this.form.submit()
                .then(response => {
                    location.replace(response.data.redirect ?? '/')
                })
                .catch(error => {
					if (error.response?.status === 422) {
						return
					}
					this.$store.toasts.handleResponseError(error)
				})
				.finally(() => {
					this.loading = false
				})
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
        <div class="form__row">
            <h2 class="form__title">{{ $conference->{'title_' . loc()} }}</h2>
            <div class="time-block">
                <time>{{ $conference->start_date->translatedFormat('d M Y') }}
                    -
                    {{ $conference->end_date->translatedFormat('d M Y') }}
                </time>

                @empty($conference->organization->{'short_name_' . loc()})
                    <span>{{ $conference->organization->{'full_name_' . loc()} }}</span>
                @else
                    <span>{{ $conference->organization->{'short_name_' . loc()} }}</span>
                @endempty
            </div>
        </div>

        <div class="form__row _three">
            <div class="form__line" :class="form.invalid('name_ru') && '_error'">
                <label class="form__label" for="u_5">@lang('auth.register.name_ru') (*)</label>
                <input id="u_5" class="input" autocomplete="off" type="text" name="name_ru" 
                    placeholder="@lang('auth.register.name_ru')" x-model="form.name_ru"
                    @input.debounce.1000ms="form.validate('name_ru')">
            </div>
            <div class="form__line" :class="form.invalid('surname_ru') && '_error'">
                <label class="form__label" for="u_6">@lang('auth.register.surname_ru') (*)</label>
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
                <label class="form__label" for="u_8">@lang('auth.register.name_en') (*)</label>
                <input id="u_8" class="input" autocomplete="off" type="text" name="name_en" 
                    placeholder="@lang('auth.register.name_en')" x-model="form.name_en"
                    @input.debounce.1000ms="form.validate('name_en')">
            </div>
            <div class="form__line" :class="form.invalid('surname_en') && '_error'">
                <label class="form__label" for="u_9">@lang('auth.register.surname_en') (*)</label>
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
                        <label class="form__label" for="f_1">{{ __('my/events/participation.participate.affiliations') }}</label>
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
								affiliationsIds() {
									let result = []
									Object.values(form.affiliations)
										.forEach(el => {
											if (el.id == '') return
											result.push(el.id)
										})
									return result
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
												<li>{{ __('my/events/participation.participate.no_suggestions') }}</li>
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
								</div>

								<div class="form__line" 
									x-show="noAffiliation" 
									:class="form.invalid('country.id') && '_error'" 
									@click.outside="showCountries = false"
								>
									<input class="form-block__input input" autocomplete="off" type="text"
										placeholder="Please insert the country"
										:value="affiliation.country?.name_en"
										@input.debounce.500ms="getCountries">
									<template x-if="form.invalid('phone')">
										<div class="form__error" x-text="form.errors.country"></div>
									</template>
									<div class="input-tips" x-show="showCountries" x-transition.opacity>
										<ul>
											<template x-for="country in countries">
												<li x-text="country.name_ru + `| ${country.name_en}`" @click="selectCountry(country, id)"></li>
											</template>
											<template x-if="countries.length === 0">
												<li>{{ __('my/events/participation.participate.no_suggestions') }}</li>
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
												<span class="checkbox__text">{{ __('my/events/participation.participate.affiliation_mistake') }}</span>
											</label>
										</div>
										<div class="checkbox">
											<input :id="'a_2' + id" class="checkbox__input" type="checkbox" :name="'handle' + id"
												x-model="noAffiliation" @change="changeNoAffiliation">
											<label :for="'a_2' + id" class="checkbox__label">
												<span class="checkbox__text">{{ __('my/events/participation.participate.no_affiliation') }}</span>
											</label>
										</div>
									</div>
								</div>
								<div class="form__line">
									<button class="form__button button button_outline" type="button" @click="remove(id)">
										{{ __('my/events/participation.participate.remove_affiliation') }}
									</button>
								</div>
							</div>
						</template>

						<div class="form__line">
							<button class="form__button button" type="button" @click="add">{{ __('my/events/participation.participate.add_affiliation') }}</button>
						</div>
                    </div>

        <div class="form__row" :class="form.invalid('orcid_id') && '_error'">
            <label class="form__label" for="c_4">{{ __('my/events/participation.participate.orcid') }}</label>
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
            <label class="form__label" for="c_5">{{ __('my/events/participation.participate.website') }}</label>
            <div class="form__line">
                <input id="c_5" class="input" autocomplete="off" type="text" name="website"
                    placeholder="http://example.ru" x-model="form.website"
                    @input.debounce.1000ms="form.validate('website')">
            </div>
            <template x-if="form.invalid('website')">
                <div class="form__error" x-text="form.errors.website"></div>
            </template>
        </div>

		<div class="form__row" :class="form.invalid('phone') && '_error'">
            <label class="form__label" for="f_10">{{ __('my/events/participation.participate.phone') }}</label>
            <input id="f_10" class="input" autocomplete="off" type="text"
                placeholder="{{ __('my/events/participation.participate.phone') }}" x-model="form.phone"
				@input.debounce.1000ms="form.validate('phone')">
			<template x-if="form.invalid('phone')">
                <div class="form__error" x-text="form.errors.phone"></div>
            </template>
        </div>

        <div class="form__row">
            <label class="form__label">{{ __('my/events/participation.participate.type') }}</label>
            <div class="d-flex-start">
                <div class="checkbox">
                    <input id="s_1" class="checkbox__input" type="radio" checked value="speaker" name="participationType" 
						x-model="form.participation_type">
                    <label for="s_1" class="checkbox__label">
                        <span class="checkbox__text">{{ __('my/events/participation.participate.reporter') }}</span>
                    </label>
                </div>
                <div class="checkbox">
                    <input id="s_2" class="checkbox__input" type="radio" value="visitor" name="participationType"
						x-model="form.participation_type">
                    <label for="s_2" class="checkbox__label">
                        <span class="checkbox__text">{{ __('my/events/participation.participate.visitor') }}</span>
                    </label>
                </div>
                {{-- <div class="checkbox">
                    <input id="s_3" class="checkbox__input" type="checkbox" value="1" name="participationType">
                    <label for="s_3" class="checkbox__label">
                        <span class="checkbox__text">Invited</span>
                    </label>
                </div> --}}
            </div>
			<template x-if="form.invalid('participation_type')">
				<div class="form__error" x-text="form.errors.participation_type"></div>
			</template>
        </div>

        <div class="form__row">
            {{-- <label class="form__label">Role</label> --}}
            <div class="checkbox">
                <input id="r_1" class="checkbox__input" type="checkbox" x-model="form.is_young">
                <label for="r_1" class="checkbox__label">
                    <span class="checkbox__text">{{ __('my/events/participation.participate.young_scientist') }}</span>
                </label>
            </div>
        </div>

        <div class="form__row">
            {{-- <p>Вы можете заполнить форму тезисов позже самостоятельно через карточку мероприятия</p> --}}
            <div class="form__btns">
                <button class="form__button button button_primary" :disabled="form.processing" type="submit">
					{{ __('my/events/participation.participate.btn') }}
					<x-loader class="tw-w-5 tw-h-4" />
				</button>
                {{-- <button class="form__button button" type="submit">Заполнить форму</button> --}}
            </div>
        </div>
    </form>
@endsection
