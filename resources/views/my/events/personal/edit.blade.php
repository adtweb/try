@extends('layouts.conference-lk')

@section('title', __('my/events/personal.edit.title'))

@section('content')

	<x-my.breadcrumbs class="tw-mt-3" :items="[
		route('events.organization-index') => __('my/events/personal.edit.breadcrumbs.1'),
		route('conference.show', $conference->slug) => $conference->{'title_'.loc()},
		'#' => __('my/events/personal.edit.breadcrumbs.2')
	]" />

    <h1 class="edit-content__title">{{ __('my/events/personal.edit.h1') }}</h1>

    <script>
        let coOrginizers = @json($conference->{'co-organizers'});
		if (coOrginizers === null) {
			coOrginizers = {}
		} else if (coOrginizers.length === 0) {
			coOrginizers = {}
		}
        let discount_students = @json($conference->discount_students);
        let discount_participants = @json($conference->discount_participants);
        let discount_special_guest = @json($conference->discount_special_guest);
        let discount_young_scientist = @json($conference->discount_young_scientist);
        let thesis_instruction = @json($conference->thesis_instruction);
    </script>
    <form action="#" class="edit-content__form form" @select-callback.camel.document="select" @submit.prevent="submit"
        x-data="{
            form: $form('post', '{{ route('conference.update', $conference->slug) }}', {
                title_ru: '{{ $conference->title_ru }}',
                title_en: '{{ $conference->title_en }}',
				organization_id: '{{ $conference->organization_id }}',
                conference_type_id: '',
                format: '{{ $conference->format->value }}',
                with_foreign_participation: {{ $conference->with_foreign_participation ? 'true' : 'false' }},
                subjects: [],
                image: '',
                website: '{{ $conference->website }}',
                need_site: {{ $conference->need_site ? 'true' : 'false' }},
                'co-organizers': coOrginizers,
                address: '{{ $conference->address }}',
                phone: '{{ $conference->phone?->raw() }}',
                email: '{{ $conference->email }}',
                start_date: '{{ $conference->start_date->format('Y-m-d') }}',
                end_date: '{{ $conference->end_date->format('Y-m-d') }}',
                timezone: '{{ $conference->timezone->value }}',
                description_ru: '{{ $conference->description_ru }}',
                description_en: '{{ $conference->description_en }}',
                lang: '',
                participants_number: '',
                report_form: '',
                whatsapp: '{{ $conference->whatsapp }}',
                telegram: '{{ $conference->telegram }}',
                price_participants: '{{ $conference->price_participants }}',
                price_visitors: '{{ $conference->price_visitors }}',
                discount_students: discount_students,
                discount_participants: discount_participants,
                discount_special_guest: discount_special_guest,
                discount_young_scientist: discount_young_scientist,
                abstracts_price: '{{ $conference->abstracts_price }}',
                abstracts_format: '',
                abstracts_lang: '',
                max_thesis_characters: {{ $conference->max_thesis_characters }},
                thesis_accept_until: '{{ $conference->thesis_accept_until?->format('Y-m-d') }}',
                thesis_edit_until: '{{ $conference->thesis_edit_until?->format('Y-m-d') }}',
                assets_load_until: '{{ $conference->assets_load_until?->format('Y-m-d') }}',
                thesis_instruction: thesis_instruction,
            }),
            formatCheckShow: {{ $conference->format->value == 'national' ? 'true' : 'false' }},
            formDisabled: false,
			loading: false,
        
            init() {
                document.querySelectorAll('select')
                    .forEach(select => this.getSelectValue(select))
				
				this.$watch('form.format', (val) => this.formatCheckShow = val == 'national')
            },
            select() {
                let select = this.$event.detail.select
                this.getSelectValue(select)
            },
            getSelectValue(select) {
                if (select.dataset.name == 'subjects') {
                    this.form.subjects = Array.from(select.querySelectorAll('option:checked'), e => +e.value)
                } else if (select.dataset.name == 'conference_type_id') {
                    this.form.conference_type_id = select.value
                } else if (select.dataset.name == 'lang') {
                    this.form.lang = select.value
                } else if (select.dataset.name == 'participants_number') {
                    this.form.participants_number = select.value
                } else if (select.dataset.name == 'report_form') {
                    this.form.report_form = select.value
                } else if (select.dataset.name == 'abstracts_format') {
                    this.form.abstracts_format = select.value
                } else if (select.dataset.name == 'abstracts_lang') {
                    this.form.abstracts_lang = select.value
                } else if (select.dataset.name == 'discount_students_unit') {
                    this.form.discount_students.unit = select.value
                    this.form.validate('discount_students')
                } else if (select.dataset.name == 'discount_participants_unit') {
                    this.form.discount_participants.unit = select.value
                    this.form.validate('discount_participants')
                } else if (select.dataset.name == 'discount_special_guest_unit') {
                    this.form.discount_special_guest.unit = select.value
                    this.form.validate('discount_special_guest')
                } else if (select.dataset.name == 'discount_young_scientist_unit') {
                    this.form.discount_young_scientist.unit = select.value
                    this.form.validate('discount_young_scientist')
                }
            },
            submit() {
                this.formDisabled = true
				this.loading = true
        
                this.form.submit()
                    .then(response => {
                        location.replace(response.data.redirect)
                    })
                    .catch(error => {
						this.$store.toasts.handleResponseError(error)
					})
					.finally(() => {
						this.formDisabled = false
						this.loading = false
					})
            },
        }">
        <div class="form__row" :class="form.invalid('title_ru') && '_error'">
            <label class="form__label" for="c_1">{{ __('my/events/personal.edit.event_name_ru') }} (*)</label>
            <input id="c_1" class="input" autocomplete="off" type="text" name="title_ru"
                placeholder="{{ __('my/events/personal.edit.event_name_ru') }}" x-model="form.title_ru"
                @input.debounce.1000ms="form.validate('title_ru')">
            <template x-if="form.invalid('title_ru')">
                <div class="form__error" x-text="form.errors.title_ru"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('title_en') && '_error'">
            <label class="form__label" for="c_2">{{ __('my/events/personal.edit.event_name_en') }} (*)</label>
            <input id="c_2" class="input" autocomplete="off" type="text" name="title_en" placeholder="{{ __('my/events/personal.edit.event_name_en') }}"
                x-model="form.title_en" @input.debounce.1000ms="form.validate('title_en')">
            <template x-if="form.invalid('title_en')">
                <div class="form__error" x-text="form.errors.title_en"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('slug') && '_error'">
            <label class="form__label" for="a_1">{{ __('my/events/personal.edit.acronim') }} (*)</label>
            <input id="a_1" class="input" autocomplete="off" type="text"
                placeholder="{{ __('my/events/personal.edit.acronim_placeholder', ['year' => date('Y')]) }}"
                value="{{ $conference->slug }}" disabled>
            <template x-if="form.invalid('slug')">
                <div class="form__error" x-text="form.errors.slug"></div>
            </template>
            <div class="form__link" x-text="location.origin + '/events/' + '{{ $conference->slug }}'"></div>
        </div>

		<div class="form__row">
			<x-form.select 
				name="organization_id"
				label="{{ __('my/events/create.organization') }} (*)" 
				option_in_form="organization_id"
			>
				@foreach ($organizations as $organization)
					<option value="{{ $organization->id }}" @if($organization->id == $conference->organization_id) selected @endif>
						{{ $organization->{'full_name_'.loc()} }}
					</option>
				@endforeach
			</x-form.select>

			<button class="button tw-mt-2" type="button" @click="$dispatch('popup', 'create-organization')">
				{{ __('my/events/create.add_organization_btn') }}
			</button>
		</div>

		<div class="form__row">
			<x-form.input-image 
				:title="__('my/events/personal.edit.logo')"
				:image="$conference->logo"
				name="image"
				:delete_url="route('conference.logo.delete', $conference->slug)"
				:cover="false"
			/>
		</div>

        <div class="form__row" :class="form.invalid('conference_type_id') && '_error'">
            <label class="form__label">{{ __('my/events/personal.edit.type') }} (*)</label>
            <select name="type" data-scroll="500" data-class-modif="form" data-name="conference_type_id">
                @foreach (conference_types() as $type)
                    <option value="{{ $type->id }}" @if ($type->id === $conference->conference_type_id) selected @endif>
                        {{ $type->{'title_' . loc()} }}</option>
                @endforeach
            </select>
            <template x-if="form.invalid('conference_type_id')">
                <div class="form__error" x-text="form.errors.conference_type_id"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('format') && '_error'">
            {{-- <label class="form__label">{{ __('my/events/personal.edit.format') }} (*)</label>
            <select name="format" data-scroll="500" data-class-modif="format" data-name="format">
                <option value="national" @if ('national' === $conference->format) selected @endif>
					{{ __('my/events/personal.edit.national') }}
				</option>
                <option value="international" @if ('international' === $conference->format) selected @endif>
					{{ __('my/events/personal.edit.international') }}
				</option>
            </select>
            <template x-if="form.invalid('format')">
                <div class="form__error" x-text="form.errors.format"></div>
            </template> --}}
			<x-form.select 
				class="tw-mb-2"
				name="format"
				label="{{ __('my/events/create.format') }} (*)" 
				option_in_form="format"
			>
				<option value="national" @if ('national' === $conference->format->value) selected @endif>
					{{ __('my/events/create.national') }}
				</option>
				<option value="international" @if ('international' === $conference->format->value) selected @endif>
					{{ __('my/events/create.international') }}
				</option>
			</x-form.select>

            <div class="checkbox" x-show="formatCheckShow" x-transition>
                <input id="chx_1" class="checkbox__input" type="checkbox" value="1"
                    name="with_foreign_participation" x-model="form.with_foreign_participation">
                <label for="chx_1" class="checkbox__label">
                    <span class="checkbox__text">{{ __('my/events/personal.edit.with_foreign_participation') }}</span>
                </label>
            </div>
        </div>

        <div class="form__row" :class="form.invalid('subjects') && '_error'">
            <label class="form__label">{{ __('my/events/personal.edit.subjects') }} (*)</label>
            <select name="form[]" data-scroll="500" multiple data-class-modif="format" data-name="subjects">
                @foreach (subjects() as $subject)
                    <option value="{{ $subject->id }}" @if ($conference->subjects->contains(fn($value) => $value->id === $subject->id)) selected @endif>
                        {{ $subject->{'title_' . app()->getLocale()} }}
                    </option>
                @endforeach
            </select>
            <template x-if="form.invalid('subjects')">
                <div class="form__error" x-text="form.errors.subjects"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('website') && '_error'">
            <label class="form__label" for="c_5">{{ __('my/events/personal.edit.site') }}</label>
            <div class="d-grid">
                <input id="c_5" class="input" autocomplete="off" type="text" name="website"
                    placeholder="http://website.com" x-model="form.website"
                    @input.debounce.1000ms="form.validate('website')">
                <div class="checkbox">
                    <input id="chx_2" class="checkbox__input" type="checkbox" value="1" name="need_site"
                        x-model="form.need_site">
                    <label for="chx_2" class="checkbox__label">
                        <span class="checkbox__text">{{ __('my/events/personal.edit.need_site') }}</span>
                    </label>
                </div>
            </div>
            <template x-if="form.invalid('website')">
                <div class="form__error" x-text="form.errors.website"></div>
            </template>
        </div>

        <div id="co-organizers" class="form__row" :class="form.invalid('co-organizers') && '_error'"
            x-data="{
                ai: 1,
                add() {
                    this.form['co-organizers'][this.ai] = ''
                    this.ai++
                },
                remove(id) {
                    delete this.form['co-organizers'][id]
                },
            }">
            <div class="form__line">
                <label class="form__label" for="c_6">{{ __('my/events/personal.edit.co-organizers') }}</label>
            </div>
            <div class="form__line">
                <template x-for="(organizer, id) in form['co-organizers']" :key="id">
                    <div class="form-list" :class="form.invalid(`co-organizers.${id}`) && '_error'">
                        <input class="input" autocomplete="off" type="text" name="form[]" data-error="Ошибка"
                            placeholder="{{ __('my/events/personal.edit.co-organizer_name') }}" x-model="form['co-organizers'][id]"
                            @change="form.validate(`co-organizers.${id}`)">
                        <template x-if="form.invalid(`co-organizers.${id}`)">
                            <div class="form__error" x-text="form.errors[`co-organizers.${id}`]"></div>
                        </template>
                        <button class="button button_outline" type="button" @click="remove(id)">
							{{ __('my/events/personal.edit.remove_co-organizer') }}
						</button>
                    </div>
                </template>
                <button class="button" type="button" @click="add">{{ __('my/events/personal.edit.add_co-organizer') }}</button>
            </div>

        </div>

        <div id="address" class="form__row" :class="form.invalid('address') && '_error'" x-data="{
            suggestions: [],
            show: false,
        }">
            <label class="form__label" for="c_7">{{ __('my/events/personal.edit.address') }} (*) </label>
            <input id="c_7" class="input" autocomplete="off" type="text" name="form[]"
                placeholder="{{ __('my/events/personal.edit.address_placeholder') }}" x-model="form.address" @input.debounce.1000ms="form.validate('address')">
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
            <label class="form__label" for="c_8">{{ __('my/events/personal.edit.phone') }}</label>
            <input id="c_8" class="input" autocomplete="off" type="text" name="form[]"
                placeholder="Телефон" x-model="form.phone" @input.debounce.1000ms="form.validate('phone')">
            <template x-if="form.invalid('phone')">
                <div class="form__error" x-text="form.errors.phone"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('email') && '_error'">
            <label class="form__label" for="c_9">{{ __('my/events/personal.edit.email') }} (*)</label>
            <input id="c_9" class="input" autocomplete="off" type="text" name="form[]"
                placeholder="mail@mail.ru" x-model="form.email" @input.debounce.1000ms="form.validate('email')">
            <template x-if="form.invalid('email')">
                <div class="form__error" x-text="form.errors.email"></div>
            </template>
        </div>

        <div class="form__row _two">
            <div class="form__line" :class="form.invalid('start_date') && '_error'">
                <label class="form__label" for="date-start">{{ __('my/events/personal.edit.start_date') }} (*)</label>
                <input id="date-start" class="input" autocomplete="off" type="date" name="form[]"
                    placeholder="__.__.____" x-model="form.start_date" @change="form.validate('start_date')">
                <template x-if="form.invalid('start_date')">
                    <div class="form__error" x-text="form.errors.start_date"></div>
                </template>
            </div>
            <div class="form__line" :class="form.invalid('end_date') && '_error'">
                <label class="form__label" for="date-start">{{ __('my/events/personal.edit.end_date') }} (*)</label>
                <input id="date-end" class="input" autocomplete="off" type="date" placeholder="__.__.____"
                    x-model="form.end_date" @change="form.validate('end_date')">
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
					<option value="{{ $timezone }}" @if($timezone == $conference->timezone->value) selected @endif>{{ $label }}</option>
				@endforeach
			</x-form.select>
		</div>

        <div class="form__row" :class="form.invalid('description_ru') && '_error'">
            <label class="form__label" for="t_1">{{ __('my/events/personal.edit.description_ru') }} (*)</label>
            <textarea id="t_1" autocomplete="off" name="description_ru" placeholder="{{ __('my/events/personal.edit.description_ru_placeholder') }}" class="input _small"
                x-model="form.description_ru" @change="form.validate('description_ru')"></textarea>
            <template x-if="form.invalid('description_ru')">
                <div class="form__error" x-text="form.errors.description_ru"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('description_en') && '_error'">
            <label class="form__label" for="t_1">{{ __('my/events/personal.edit.description_en') }} (*)</label>
            <textarea id="t_1" autocomplete="off" name="description_en" placeholder="{{ __('my/events/personal.edit.description_en_placeholder') }}" class="input _small"
                x-model="form.description_en" @change="form.validate('description_en')"></textarea>
            <template x-if="form.invalid('description_en')">
                <div class="form__error" x-text="form.errors.description_en"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('lang') && '_error'">
            <label class="form__label">{{ __('my/events/personal.edit.lang') }} (*)</label>
            <select name="form[]" data-scroll="500" data-class-modif="form" data-name="lang">
                <option value="ru" @if ('ru' === $conference->lang->value) selected @endif>{{ __('my/events/personal.edit.lang_ru') }}</option>
                <option value="en" @if ('en' === $conference->lang->value) selected @endif>{{ __('my/events/personal.edit.lang_en') }}</option>
                <option value="mixed" @if ('mixed' === $conference->lang->value) selected @endif>{{ __('my/events/personal.edit.lang_mixed') }}</option>
                <option value="other" @if ('other' === $conference->lang->value) selected @endif>{{ __('my/events/personal.edit.lang_other') }}</option>
            </select>
            <template x-if="form.invalid('lang')">
                <div class="form__error" x-text="form.errors.lang"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('participants_number') && '_error'">
            <label class="form__label">{{ __('my/events/personal.edit.participants_number') }}</label>
            <select data-name="participants_number">
				@foreach (Src\Domains\Conferences\Enums\ParticipantsNumber::cases() as $number)
					<option value="{{ $number->value }}" @if ($number->value === $conference->participants_number) selected @endif>
						{{ $number->toString() }}
					</option>
				@endforeach
            </select>
            <template x-if="form.invalid('participants_number')">
                <div class="form__error" x-text="form.errors.participants_number"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('report_form') && '_error'">
            <label class="form__label">{{ __('my/events/personal.edit.report_form') }}</label>
            <select data-name="report_form">
				@foreach (Src\Domains\Conferences\Enums\ConferenceReportForm::cases() as $reportForm)
					<option value="{{ $reportForm->value }}" @if ($reportForm === $conference->report_form) selected @endif>
						{{ $reportForm->toString() }}
					</option>
				@endforeach
            </select>
            <template x-if="form.invalid('report_form')">
                <div class="form__error" x-text="form.errors.report_form"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('whatsapp') && '_error'">
            <label class="form__label" for="c_10">{{ __('my/events/personal.edit.whatsapp') }}</label>
            <input id="c_10" class="input" autocomplete="off" type="text" name="form[]"
                placeholder="https://wa.me/791200000000" x-model="form.whatsapp" @change="form.validate('whatsapp')">
            <template x-if="form.invalid('whatsapp')">
                <div class="form__error" x-text="form.errors.whatsapp"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('telegram') && '_error'">
            <label class="form__label" for="c_11">{{ __('my/events/personal.edit.telegram') }}</label>
            <input id="c_11" class="input" autocomplete="off" type="text" name="form[]"
                placeholder="https://t.me/yournickname" x-model="form.telegram" @change="form.validate('telegram')">
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
            <label class="form__label">{{ __('my/events/personal.edit.price_participants') }}</label>
            <div class="checkbox">
                <input id="chx_11" class="checkbox__input" type="checkbox" value="1"
                    name="price_participants_check" @change="change" x-model="show">
                <label for="chx_11" class="checkbox__label">
                    <span class="checkbox__text">Есть</span>
                </label>
            </div>
            <input class="input" autocomplete="off" type="text" name="form[]" placeholder="Сумма оплаты"
                x-show="show" x-transition x-model="form.price_participants"
                @change="form.validate('price_participants')">
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
            <label class="form__label">{{ __('my/events/personal.edit.price_visitors') }}</label>
            <div class="checkbox">
                <input id="chx_12" class="checkbox__input" type="checkbox" value="1"
                    name="price_visitors_check" @change="change" x-model="show">
                <label for="chx_12" class="checkbox__label">
                    <span class="checkbox__text">Есть</span>
                </label>
            </div>
            <input class="input" autocomplete="off" type="text" name="price_visitors" placeholder="Сумма оплаты"
                x-show="show" x-transition x-model="form.price_visitors" @change="form.validate('price_visitors')">
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
            <label class="form__label">{{ __('my/events/personal.edit.abstracts_price') }}</label>
            <div class="checkbox">
                <input id="chx_13" class="checkbox__input" type="checkbox" value="1"
                    name="abstracts_price_check" @change="change" x-model="show">
                <label for="chx_13" class="checkbox__label">
                    <span class="checkbox__text">Есть</span>
                </label>
            </div>
            <input class="input" autocomplete="off" type="text" placeholder="Сумма оплаты" x-show="show"
                x-transition x-model="form.abstracts_price" @change="form.validate('abstracts_price')">
            <template x-if="form.invalid('abstracts_price') && show">
                <div class="form__error" x-text="form.errors.abstracts_price"></div>
            </template>
        </div>

        <div class="form__row">
            <label class="form__label">{{ __('my/events/personal.edit.discounts') }}</label>
            <div class="checkbox-block" :class="form.invalid('discount_students') && '_error'" x-data="{
                show: false,
            
                change() {
                    if (this.$el.checked) return
                    this.form.discount_students.amount = ''
                }
            }">
                <div class="checkbox">
                    <input id="chx_3" class="checkbox__input" type="checkbox" x-model="show" @change="change">
                    <label for="chx_3" class="checkbox__label">
                        <span class="checkbox__text">{{ __('my/events/personal.edit.discount_students') }}</span>
                    </label>
                </div>
                <div class="checkbox-block__input" x-show="show" x-transition>
                    <div class="form__line">
                        <input class="input" autocomplete="off" type="text" name="form[]"
                            placeholder="Размер скидки" x-model="form.discount_students.amount"
                            @change="form.validate('discount_students')">
                    </div>
                    <div class="form__line">
                        <select data-class-modif="form" data-name="discount_students_unit">
                            <option value="RUB" selected>В рублях</option>
                            <option value="percent">В %</option>
                        </select>
                    </div>
                </div>
                <template x-if="form.invalid('discount_students') && show">
                    <div class="form__error" x-text="form.errors.discount_students"></div>
                </template>
            </div>
            <div class="checkbox-block" :class="form.invalid('discount_participants') && '_error'"
                x-data="{
                    show: false,
                
                    change() {
                        if (this.$el.checked) return
                        this.form.discount_participants.amount = ''
                    }
                }">
                <div class="checkbox">
                    <input id="chx_4" checked class="checkbox__input" type="checkbox" x-model="show"
                        @change="change">
                    <label for="chx_4" class="checkbox__label">
                        <span class="checkbox__text">{{ __('my/events/personal.edit.discount_participants') }}</span>
                    </label>
                </div>
                <div class="checkbox-block__input" x-show="show" x-transition>
                    <div class="form__line">
                        <input class="input" autocomplete="off" type="text" placeholder="Размер скидки"
                            x-model="form.discount_participants.amount" @change="form.validate('discount_participants')">
                    </div>
                    <div class="form__line">
                        <select data-name="discount_participants_unit" data-class-modif="form">
                            <option value="RUB" selected>В рублях</option>
                            <option value="percent">В %</option>
                        </select>
                    </div>
                </div>
                <template x-if="form.invalid('discount_participants') && show">
                    <div class="form__error" x-text="form.errors.discount_participants"></div>
                </template>
            </div>
            <div class="checkbox-block" :class="form.invalid('discount_special_guest') && '_error'"
                x-data="{
                    show: false,
                
                    change() {
                        if (this.$el.checked) return
                        this.form.discount_special_guest.amount = ''
                    }
                }">
                <div class="checkbox">
                    <input id="chx_5" class="checkbox__input" type="checkbox" x-model="show" @change="change">
                    <label for="chx_5" class="checkbox__label">
                        <span class="checkbox__text">{{ __('my/events/personal.edit.special_guest') }}</span>
                    </label>
                </div>
                <div class="checkbox-block__input" x-show="show" x-transition>
                    <div class="form__line">
                        <input class="input" autocomplete="off" type="text" placeholder="Размер скидки"
                            x-model="form.discount_special_guest.amount"
                            @change="form.validate('discount_special_guest')">
                    </div>
                    <div class="form__line">
                        <select data-name="discount_special_guest_unit" data-class-modif="form">
                            <option value="RUB" selected>В рублях</option>
                            <option value="percent">В %</option>
                        </select>
                    </div>
                </div>
                <template x-if="form.invalid('discount_special_guest') && show">
                    <div class="form__error" x-text="form.errors.discount_special_guest"></div>
                </template>
            </div>
            <div class="checkbox-block" :class="form.invalid('discount_young_scientist') && '_error'"
                x-data="{
                    show: false,
                
                    change() {
                        if (this.$el.checked) return
                        this.form.discount_young_scientist.amount = ''
                    }
                }">
                <div class="checkbox">
                    <input id="chx_6" checked class="checkbox__input" type="checkbox" x-model="show"
                        @change="change">
                    <label for="chx_6" class="checkbox__label">
                        <span class="checkbox__text">{{ __('my/events/personal.edit.young_scientist') }}</span>
                    </label>
                </div>
                <div class="checkbox-block__input" x-show="show" x-transition>
                    <div class="form__line">
                        <input class="input" autocomplete="off" type="text" placeholder="Размер скидки"
                            x-model="form.discount_young_scientist.amount"
                            @change="form.validate('discount_young_scientist')">
                    </div>
                    <div class="form__line">
                        <select data-name="discount_young_scientist_unit" data-class-modif="form">
                            <option value="RUB" selected>В рублях</option>
                            <option value="percent">В %</option>
                        </select>
                    </div>
                </div>
                <template x-if="form.invalid('discount_young_scientist') && show">
                    <div class="form__error" x-text="form.errors.discount_young_scientist"></div>
                </template>
            </div>
        </div>

        @php
            $hasThesis = $conference->theses()->exists();
        @endphp

        <div class="form__row" :class="form.invalid('abstracts_format') && '_error'">
            <label class="form__label">{{ __('my/events/personal.edit.abstracts_format') }}</label>
            <select name="abstracts_format" data-scroll="500" data-class-modif="form" data-name="abstracts_format"
                @if ($hasThesis) disabled @endif>
                <option value="A4" @if ('A4' === $conference->abstracts_format->value) selected @endif>А4</option>
                <option value="A5" @if ('A5' === $conference->abstracts_format->value) selected @endif>А5</option>
            </select>
            <template x-if="form.invalid('abstracts_format')">
                <div class="form__error" x-text="form.errors.abstracts_format"></div>
            </template>
        </div>

        {{-- <div class="form__row" :class="form.invalid('email') && '_error'">
                        <label class="form__label">Наполнение сборника тезисов</label>
                        <select name="form[]" data-scroll="500" data-class-modif="form">
                            <option value="1" selected>Цветное</option>
                            <option value="2">Черно-белое</option>
                        </select>
                    </div> --}}

        <div class="form__row" :class="form.invalid('abstracts_lang') && '_error'">
            <label class="form__label">{{ __('my/events/personal.edit.abstracts_lang') }}</label>
            <select name="abstracts_lang" @if ($hasThesis) disabled @endif data-scroll="500"
                data-class-modif="form" data-name="abstracts_lang">
                <option value="ru" @if ('ru' === $conference->abstracts_lang->value) selected @endif>{{ __('my/events/personal.edit.abstracts_lang_ru') }}</option>
                <option value="en" @if ('en' === $conference->abstracts_lang->value) selected @endif>{{ __('my/events/personal.edit.abstracts_lang_en') }}</option>
            </select>
            <template x-if="form.invalid('abstracts_lang')">
                <div class="form__error" x-text="form.errors.abstracts_lang"></div>
            </template>
        </div>

        <div class="form__row" :class="form.invalid('max_thesis_characters') && '_error'">
            <label class="form__label" for="c_21">{{ __('my/events/personal.edit.max_thesis_characters') }} (*)</label>
            <input id="c_21" class="input" autocomplete="off" name="max_thesis_characters"
                placeholder="{{ __('my/events/personal.edit.max_thesis_characters_placeholder') }}"
                @if ($hasThesis) disabled @endif x-model="form.max_thesis_characters"
                @input.debounce.1000ms="form.validate('max_thesis_characters')">
            <template x-if="form.invalid('max_thesis_characters')">
                <div class="form__error" x-text="form.errors.max_thesis_characters"></div>
            </template>
        </div>

        <div class="form__row _three">
            <div class="form__line tw-flex tw-flex-col" :class="form.invalid('thesis_accept_until') && '_error'">
                <label class="form__label" for="thesis_accept_until">{{ __('my/events/personal.edit.thesis_accept_until') }} (*)</label>
                <input id="thesis_accept_until" class="input tw-mt-auto" autocomplete="off" type="date"
                    placeholder="__.__.____" x-model="form.thesis_accept_until"
                    @change="form.validate('thesis_accept_until')">
                <template x-if="form.invalid('thesis_accept_until')">
                    <div class="form__error" x-text="form.errors.thesis_accept_until"></div>
                </template>
            </div>
            <div class="form__line tw-flex tw-flex-col" :class="form.invalid('thesis_edit_until') && '_error'">
                <label class="form__label" for="thesis_edit_until">{{ __('my/events/personal.edit.thesis_edit_until') }} (*)</label>
                <input id="thesis_edit_until" class="input tw-mt-auto" autocomplete="off" type="date" placeholder="__.__.____"
                    x-model="form.thesis_edit_until" @change="form.validate('thesis_edit_until')">
                <template x-if="form.invalid('thesis_edit_until')">
                    <div class="form__error" x-text="form.errors.thesis_edit_until"></div>
                </template>
            </div>
            <div class="form__line tw-flex tw-flex-col" :class="form.invalid('assets_load_until') && '_error'">
                <label class="form__label" for="assets_load_until">{{ __('my/events/personal.edit.assets_load_until') }} (*)</label>
                <input id="assets_load_until" class="input tw-mt-auto" autocomplete="off" type="date" placeholder="__.__.____"
                    x-model="form.assets_load_until" @change="form.validate('assets_load_until')">
                <template x-if="form.invalid('assets_load_until')">
                    <div class="form__error" x-text="form.errors.assets_load_until"></div>
                </template>
            </div>
        </div>

        <div class="form__row" :class="form.invalid('thesis_instruction') && '_error'">
            <label class="form__label" for="t_1">{{ __('my/events/personal.edit.thesis_instruction') }}</label>
            <textarea id="t_1" autocomplete="off" name="thesis_instruction" placeholder="{{ __('my/events/personal.edit.thesis_instruction') }}"
                class="input" style="height: 300px" x-model="form.thesis_instruction"
                @change="form.validate('thesis_instruction')"></textarea>
            <template x-if="form.invalid('thesis_instruction')">
                <div class="form__error" x-text="form.errors.thesis_instruction"></div>
            </template>
        </div>

        <div class="form__row">
            <button class="form__button button button_primary" type="submit"
                :disabled="form.processing || formDisabled">
                {{ __('my/events/personal.edit.btn') }}
				<x-loader class="tw-w-5 tw-h-4"/>
            </button>
        </div>
        <div class="form__row">
            <template x-if="form.hasErrors">
                <div class="form__error">{{ __('my/events/personal.edit.errors') }}</div>
            </template>
        </div>
    </form>
@endsection

@section('popup')
	@include('partials.modals.create-organization')
@endsection
