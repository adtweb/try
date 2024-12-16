@extends('layouts.auth')

@section('title', __('my/events/theses.edit.title'))

@section('h1', __('my/events/theses.edit.h1'))

@section('back_link', route('conference.show', $conference->slug))

@section('head_scripts')
	@vite(['resources/js/wysiwyg.js'])
@endsection

@php
	$lang = $conference->abstracts_lang->value;
@endphp

@section('content')
    <script>
		let thesisTitle = @json($thesis->title);
		let thesisText = @json($thesis->text);
		let authors = @json($thesis->authors ?? []);
		if (authors.lenght == 0) authors = {};
		let keys = Object.keys(authors);
		keys.forEach(key => {
			if (authors[key].affiliations.length === 0) {
				authors[key].affiliations = {}
			}
		});

		let reporter = @json($thesis->reporter ?? []);
		let contact = @json($thesis->contact ?? []);
    </script>

    <form class="registration__form form" 
	@select-callback.camel.document="select"
	@submit.prevent="submit" 
	x-data="{
        form: $form('post', '{{ route('theses.update', [$conference->slug, $thesis->id]) }}', {
			@if ($conference->sections->isNotEmpty())
				section_id: {{ $thesis->section_id }},
			@endif
            report_form: '{{ $thesis->report_form }}',
			solicited_talk: {{ $thesis->solicited_talk ? 'true' : 'false' }},
            title: '',
            authors: authors,
			reporter: reporter,
			contact: contact,
			text: '',
        }),
		loading: false,

		init() {
			setTimeout(() => {
				document.querySelectorAll('select')
					.forEach(select => this.getSelectValue(select))
			}, 2000)
		},
        submit() {
			this.getEditorsData()

			this.loading = true

            this.form.submit()
                .then(response => {
                    location.replace(response.data.redirect ?? '/')
                })
                .catch(error => this.$store.toasts.handleResponseError(error))
				.finally(() => {
					this.loading = false
				})
        },
		select() {
			let select = this.$event.detail.select
			this.getSelectValue(select)
		},
		getSelectValue(select) {
			if (select.dataset.name == 'section_id') {
				this.form.section_id = select.value
			} else if (select.dataset.name == 'report_form') {
				this.form.report_form = select.value
			} else if (select.dataset.name == 'reporter') {
				this.form.reporter.id = select.value
			} else if (select.dataset.name == 'contact') {
				this.form.contact.id = select.value
			}
		},
		postpone(ready, make) {
			if (ready()) {
				make()
				return
			}

			setTimeout(() => this.postpone(ready, make), 500);
		},
		getEditorsData() {
			this.form.title = editorTitle.getData()
			this.form.text = editorText.getData()
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

        @if ($conference->sections->count() > 0)
            <div class="form__row">
                <label class="form__label">{{ __('my/events/theses.edit.choose_section') }} (*)</label>
                <select name="section_id" data-scroll="500" data-class-modif="form" data-name="section_id">
                    <option value="" selected>{{ __('my/events/theses.edit.choose_section') }}</option>
                    @foreach ($conference->sections as $section)
                        <option value="{{ $section->id }}" @if($section->id === $thesis->section_id) selected @endif>
							{{ $section->{'title_' . loc()} }}
						</option>
                    @endforeach
                </select>
				<template x-if="form.invalid(`section_id`)">
					<div class="form__error" x-text="form.errors[`section_id`]"></div>
				</template>
            </div>
        @endif

        <div class="form__row">
            <label class="form__label">{{ __('my/events/theses.edit.report_form') }} (*)</label>
            <select name="report_form" data-scroll="500" data-class-modif="form" data-name="report_form">
				@foreach (Src\Domains\Conferences\Enums\ThesisReportForm::cases() as $reportForm)
					<option value="{{ $reportForm->value }}" @if($reportForm === $thesis->report_form) selected @endif>
						{{ $reportForm->toString() }}
					</option>
				@endforeach
            </select>
        </div>

		<div class="form__row">
			 <div class="checkbox">
				 <input id="solicited_talk" class="checkbox__input" type="checkbox"
					 name="solicited_talk" x-model="form.solicited_talk">
				 <label for="solicited_talk" class="checkbox__label">
					 <span class="checkbox__text">{{ __('my/events/theses.edit.solicited_talk') }}</span>
				 </label>
			 </div>
			 <div>{{ __('my/events/theses.edit.solicited_talk_hint') }}</div>
		 </div>

        <label class="form__label _mb0">{{ __('my/events/theses.edit.authors_list') }} (*) </label>

		<div x-data="{
			ai: null,
			selectClass: null,

			init() {
				this.ai = +Object.keys(this.form.authors).pop() + 1

				this.postpone(this.checkModules, this.saveSelects)
			},
			postpone() {
				setTimeout(() => {
					if (typeof modules_flsModules == 'undefined') {
						this.postpone()
						return;
					}
					
					this.selectClass = modules_flsModules.select
				}, 500)
			},
			add() {
                this.form.authors[this.ai] = {
					@if ($lang === 'ru')
						name_ru: '',
						surname_ru: '',
						middle_name_ru: '',
					@endif
					@if ($lang === 'en')
						name_en: '',
						surname_en: '',
						middle_name_en: '',
					@endif
                    affiliations: {},
                }
                this.ai++
            },
            remove(id) {
                delete this.form.authors[id]
            },
			updateSelects() {
				this.selectClass.updateSelect(document.querySelector('#reporter'))
				this.selectClass.updateSelect(document.querySelector('#contact'))
			},
			inputName(id) {
				this.updateSelects()

				this.form.validate(`authors.${id}.name_{{ $lang }}`)
			},
			inputSurname(id) {
				this.updateSelects()

				this.form.validate(`authors.${id}.surname_{{ $lang }}`)
			},
			removeAuthor(id) {
				this.remove(id)

				this.$nextTick(() => {
					this.updateSelects()
				})
			}
		}">
			<div class="tw-flex tw-flex-col tw-gap-[50px]">
				<template x-for="author, id in form.authors" x-key="id">
					<div class="author">
						@if ($lang === 'ru')
							<div class="form__row _three">
								<div class="form__line" :class="form.invalid(`authors.${id}.name_ru`) && '_error'">
									<label class="form__label" :for="'u_5'+id">@lang('auth.register.name_ru') (*)</label>
									<input :id="'u_5'+id" class="input" autocomplete="off" type="text" name="name_ru"
										data-error="Ошибка" placeholder="@lang('auth.register.name_ru')" x-model="form.authors[id].name_ru"
										@input.debounce.500ms="inputName(id)">
								</div>
								<div class="form__line" :class="form.invalid(`authors.${id}.surname_ru`) && '_error'">
									<label class="form__label" :for="'u_6'+id">@lang('auth.register.surname_ru') (*)</label>
									<input :id="'u_6'+id" class="input" autocomplete="off" type="text" name="surname_ru"
										data-error="Ошибка" placeholder="@lang('auth.register.surname_ru')" x-model="form.authors[id].surname_ru"
										@input.debounce.500ms="inputSurname(id)">
								</div>
								<div class="form__line" :class="form.invalid(`authors.${id}.middle_name_ru`) && '_error'">
									<label class="form__label" :for="'u_7'+id">@lang('auth.register.middle_name_ru')</label>
									<input :id="'u_7'+id" class="input" autocomplete="off" type="text" name="middle_name_ru"
										data-error="Ошибка" placeholder="@lang('auth.register.middle_name_ru')" x-model="form.authors[id].middle_name_ru"
										@input.debounce.1000ms="form.validate(`authors.${id}.middle_name_ru`)">
								</div>
							</div>
							<template x-if="form.invalid(`authors.${id}.name_ru`)">
								<div class="form__error" x-text="form.errors[`authors.${id}.name_ru`]"></div>
							</template>
							<template x-if="form.invalid(`authors.${id}.surname_ru`)">
								<div class="form__error" x-text="form.errors[`authors.${id}.surname_ru`]"></div>
							</template>
							<template x-if="form.invalid(`authors.${id}.middle_name_ru`)">
								<div class="form__error" x-text="form.errors[`authors.${id}.middle_name_ru`]"></div>
							</template>
						@endif
							
						@if ($lang === 'en')
							<div class="form__row _three">
								<div class="form__line" :class="form.invalid(`authors.${id}.name_en`) && '_error'">
									<label class="form__label" :for="'u_8'+id">@lang('auth.register.name_en') (*)</label>
									<input :id="'u_8'+id" class="input" autocomplete="off" type="text" name="name_en"
										data-error="Ошибка" placeholder="@lang('auth.register.name_en')" x-model="form.authors[id].name_en"
										@input.debounce.500ms="inputName(id)">
								</div>
								<div class="form__line" :class="form.invalid(`authors.${id}.surname_en`) && '_error'">
									<label class="form__label" :for="'u_9'+id">@lang('auth.register.surname_en') (*)</label>
									<input :id="'u_9'+id" class="input" autocomplete="off" type="text" name="surname_en"
										data-error="Ошибка" placeholder="@lang('auth.register.surname_en')" x-model="form.authors[id].surname_en"
										@input.debounce.500ms="inputSurname(id)">
								</div>
								<div class="form__line" :class="form.invalid(`authors.${id}.middle_name_en`) && '_error'">
									<label class="form__label" :for="'u_10'+id">@lang('auth.register.middle_name_en')</label>
									<input :id="'u_10'+id" class="input" autocomplete="off" type="text" name="middle_name_en"
										data-error="Ошибка" placeholder="@lang('auth.register.middle_name_en')"
										x-model="form.authors[id].middle_name_en"
										@input.debounce.1000ms="form.validate(`authors.${id}.middle_name_en`)">
								</div>
							</div>
							<template x-if="form.invalid(`authors.${id}.name_en`)">
								<div class="form__error" x-text="form.errors[`authors.${id}.name_en`]"></div>
							</template>
							<template x-if="form.invalid(`authors.${id}.surname_en`)">
								<div class="form__error" x-text="form.errors[`authors.${id}.surname_en`]"></div>
							</template>
							<template x-if="form.invalid(`authors.${id}.middle_name_en`)">
								<div class="form__error" x-text="form.errors[`authors.${id}.middle_name_en`]"></div>
							</template>
						@endif
		
						<div class="form__row">
							<div id="affiliations" class="form__row" x-data="{
								ai: Object.keys(author.affiliations).length > 0 ? +Object.keys(author.affiliations).pop() + 1 : 1,
							
								add() {
									if (Object.keys(author.affiliations).length >= 5) return
									author.affiliations[this.ai] = {
										id: '',
										@if ($lang === 'ru')
											title_ru: '',
										@endif
										@if ($lang === 'en')
											title_en: '',
										@endif
										country: {},
										has_mistake: false,
										no_affiliation: false,
									}
									this.ai++
								},
								remove(id) {
									delete author.affiliations[id]
								},
								affiliationsIds() {
									let result = []
									Object.values(author.affiliations)
										.forEach(el => {
											if (el.id == '') return
											result.push(el.id)
										})
									return result
								},
							}">
								<label class="form__label" for="f_1">{{ __('my/events/theses.edit.affiliations') }}</label>
								<template x-for="affiliation, affId in author.affiliations" x-key="affId">
									<div class="affiliation form__line" x-data="{
										suggestions: [],
										countries: [],
										show: false,
										showCountries: false,
			
										getSuggestions() {
											if (author.affiliations[affId].no_affiliation || author.affiliations[affId].has_mistake) return
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
											author.affiliations[id].id = suggestion.id
											author.affiliations[id].title_ru = suggestion.title_ru
											author.affiliations[id].title_en = suggestion.title_en
											this.show = false
										},
										selectCountry(country, id) {
											author.affiliations[id].country = country
											this.showCountries = false
										},
										changeMistake() {
											if (this.$el.checked) {
												author.affiliations[affId].no_affiliation = false
											} else {
												author.affiliations[affId].id = ''
												author.affiliations[affId].title_ru = ''
												author.affiliations[affId].title_en = ''
											}
											author.affiliations[affId].has_mistake = this.$el.checked
											author.affiliations[affId].country = {}
										},
										changeNoAffiliation() {
											author.affiliations[affId].id = ''
											author.affiliations[affId].title_ru = ''
											author.affiliations[affId].title_en = ''
											author.affiliations[affId].no_affiliation = this.$el.checked
	
											if (this.$el.checked) {
												author.affiliations[affId].has_mistake = false
												author.affiliations[affId].country.id = ''
											} else {
												author.affiliations[affId].country = {}
											}
										},
										placeholderRu() {
											if (author.affiliations[affId]?.no_affiliation) {
												return 'Укажите аффилиацию на русском языке (если применимо)'
											}
	
											return 'Начните вводить название организации на русском языке, появится выпадающий список. Если Вашей организации нет в списке, отметьте чекбокс ниже'
										},
										placeholderEn() {
											if (author.affiliations[affId]?.no_affiliation) {
												return 'Please insert your affiliation in English'
											}
	
											return 'Start typing name of your organization'
										},
									}">
										<div class="form__line" @click.outside="show = false">
											<textarea autocomplete="off" :placeholder="placeholder{{ ucfirst($lang) }}" class="input"
												:class="form.invalid(`affiliations.${affId}.title_{{ $lang }}`) && '_error'" x-model="author.affiliations[affId].title_{{ $lang }}"
												@input.debounce.500ms="getSuggestions"></textarea>
											<template x-if="form.invalid(`affiliations.${affId}.title_{{ $lang }}`)">
												<div class="form__error" x-text="form.errors[`affiliations.${affId}.title_{{ $lang }}`]">
												</div>
											</template>
											<div class="input-tips" x-show="show" x-transition.opacity>
												<ul>
													<template x-for="suggestion in suggestions">
														<li x-text="suggestion.title_{{ $lang }}" @click="select(suggestion, affId)"></li>
													</template>
													<template x-if="suggestions.length === 0">
														<li>{{ __('my/events/theses.edit.empty_search') }}</li>
													</template>
												</ul>
											</div>
										</div>
	
										<div class="form__line" 
											x-show="author.affiliations[affId]?.no_affiliation" 
											:class="form.invalid(`authors.${id}.affiliations.${affId}.country.id`) && '_error'" 
											@click.outside="showCountries = false"
										>
											<input class="form-block__input input" autocomplete="off" type="text"
												placeholder="Начните печатать страну аффилиации и выберите из выпадающего списка"
												:value="affiliation.country?.name_ru ? affiliation.country?.name_ru + ' | ' + affiliation.country?.name_en : ''"
												@input.debounce.500ms="getCountries"
											>
											<template x-if="form.invalid(`authors.${id}.affiliations.${affId}.country.id`)">
												<div class="form__error" x-text="form.errors[`authors.${id}.affiliations.${affId}.country.id`]"></div>
											</template>
											<div class="input-tips" x-show="showCountries" x-transition.opacity>
												<ul>
													<template x-for="country in countries">
														<li x-text="country.name_ru + `| ${country.name_en}`" @click="selectCountry(country, affId)"></li>
													</template>
													<template x-if="countries.length === 0">
														<li>{{ __('my/events/theses.edit.empty_search') }}</li>
													</template>
												</ul>
											</div>
										</div>
		
										<div class="form__line">
											<div class="checkbox-items">
												<div class="checkbox">
													<input :id="'a_1' + id + affId" class="checkbox__input" type="checkbox"
														:name="'handle' + id + affId" x-model="author.affiliations[affId].has_mistake" @change="changeMistake">
													<label :for="'a_1' + id + affId" class="checkbox__label">
														<span class="checkbox__text">{{ __('my/events/theses.edit.has_mistake') }}</span>
													</label>
												</div>
												<div class="checkbox">
													<input :id="'a_2' + id + affId" class="checkbox__input" type="checkbox"
														:name="'handle' + id + affId" x-model="author.affiliations[affId].no_affiliation"
														@change="changeNoAffiliation">
													<label :for="'a_2' + id + affId" class="checkbox__label">
														<span class="checkbox__text">{{ __('my/events/theses.edit.no_affiliation') }}</span>
													</label>
												</div>
											</div>
										</div>
										<div class="form__line">
											<button class="form__button button button_outline" type="button"
												@click="remove(affId)">
												{{ __('my/events/theses.edit.remove_affiliation') }}
											</button>
										</div>
									</div>
								</template>
		
								<div class="form__line">
									<button class="form__button button" type="button" @click="add">
										{{ __('my/events/theses.edit.add_affiliation') }}
									</button>
								</div>
							</div>
						</div>
		
						<div class="form__row">
							<button class="form__button button button_outline" type="button" @click="removeAuthor(id)">{{ __('my/events/theses.edit.remove_author') }}</button>
						</div>
		
					</div>
				</template>
			</div>
			<div class="form__row" style="margin-top: 10px">
				<button class="form__button button" type="button" @click="add()">{{ __('my/events/theses.edit.add_author') }}</button>
			</div>
		</div>


        <div class="form__row" id="reporter">
            <label class="form__label">{{ __('my/events/theses.edit.reporter') }} (*)</label>
            <select name="form[]" data-scroll="500" data-class-modif="form" data-name="reporter">
                <template x-for="author, key in form.authors" x-key="key"> 
					<option 
						:value="key" 
						:selected="key == form.reporter.id"
						x-text="`${author.name_{{ $lang }}} ${author.surname_{{ $lang }}}`"
					></option>
				</template>
            </select>

            <div class="checkbox">
                <input id="d_1" class="checkbox__input" type="checkbox" x-model="form.reporter.is_young">
                <label for="d_1" class="checkbox__label">
                    <span class="checkbox__text">{{ __('my/events/theses.edit.young') }}</span>
                </label>
            </div>
        </div>

        <div class="form__row" id="contact">
            <label class="form__label">{{ __('my/events/theses.edit.contact') }} (*)</label>
            <select name="form[]" data-scroll="500" data-class-modif="form" data-name="contact">
				<template x-for="author, key in form.authors" x-key="key">
					<option 
						:value="key" 
						:selected="key == form.contact.id"
						x-text="`${author.name_{{ $lang }}} ${author.surname_{{ $lang }}}`"
					></option>
				</template>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="c_1">{{ __('my/events/theses.edit.contact_email') }} (*)</label>
            <input id="c_1" class="input" autocomplete="off" type="text" name="form[]"
                placeholder="Enter e-mail address"  x-model="form.contact.email">
        </div>

		@if ($conference->thesis_instruction)
			<div class="form__row">
				<label class="form__label">{{ __('my/events/theses.edit.instructions') }}</label>
				{!! nl2br(htmlspecialchars($conference->thesis_instruction)) !!}
			</div>
		@endif

		@php
			if ($lang === 'en') {
				$titlePlaceholder = 'Заголовок на английском языке';
				$textPlaceholder = 'Текст на английском языке';
			}
			if ($lang === 'ru') {
				$titlePlaceholder = 'Заголовок на русском языке';
				$textPlaceholder = 'Текст на русском языке';
			}
		@endphp

		<div class="form__row editor-title" :class="form.invalid('title') && '_error'" x-data="{
			init() {
				let check = () => typeof ClassicEditor !== 'undefined'
				let make = () => {
					ClassicEditor
					.create(document.querySelector( '#editor-title' ), TitleEditorSettings)
					.then(editor => {
						editor.editing.view.document.getRoot( 'main' ).placeholder = '{{ $titlePlaceholder }}'
						editor.setData(thesisTitle)
						window.editorTitle = editor
					})
					.catch( error => {
						console.error( error );
					} );
				}
				this.postpone(check, make)
			},
		}">
            <label class="form__label" for="n_1">{{ __('my/events/theses.edit.report_title') }} (*)</label>
			<style>
				.editor-title .ck-content {
					height: 40px;
				}
			</style>
			<div id="editor-title"></div>
            <template x-if="form.invalid('title')">
                <div class="form__error" x-text="form.errors.title"></div>
            </template>
        </div>

        <div class="form__row" 
		@text-editor-update.document="textUpdate"
		x-data="{
			textCount: 0,

			init() {
				let check = () => typeof ClassicEditor !== 'undefined'
				let make = () => {
					ClassicEditor
					.create(document.querySelector( '#editor-text' ), TextEditorSettings)
					.then(editor => {
						editor.editing.view.document.getRoot( 'main' ).placeholder = '{{ $textPlaceholder }}'
						editor.setData( thesisText )
						window.editorText = editor
					})
					.catch( error => {
						console.error( error );
					} );
				}
				this.postpone(check, make)
		},
			textUpdate() {
				this.textCount = this.$event.detail.characters
			},
		}">
            <label class="form__label" for="t_1">{{ __('my/events/theses.edit.report_text') }} (*)</label>
			<div class="form__line">
				<style>
					.ck-content {
						height: 300px;
					}
				</style>
				<div id="editor-text"></div>
			</div>
			<div class="form__line">
				{{ __('my/events/theses.edit.simbols') }}:
				<span id="characters" x-text="textCount"></span>
				<span>/</span>
				<span>{{ $conference->max_thesis_characters }}</span>
			</div>
			<div class="form__line">
				<template x-if="form.invalid('text')">
					<div class="form__error" x-text="form.errors.text"></div>
				</template>
			</div>
        </div>

        <div class="form__row">
            <div class="form__btns">
                <button 
					class="form__button button button_primary" 
					type="button"
					@click="getPdf"
					x-data="{
						loading: false,

						async getPdf() {
							this.getEditorsData()

							form.touch(['section_id', 'title', 'text']).validate();

							if (this.form.title === '' || this.form.text === '') {
								return
							}

							if ((typeof this.form.section_id !== 'undefined') && this.form.section_id == null) {
								return
							}

							this.loading = true

							axios
								.post(
									'{{ route('pdf.thesis.preview', $conference->slug) }}',
									this.form,
									{responseType: 'blob'}
								)
								.then(res => {
									let blob = new Blob([res.data], {
										type: 'application/pdf',
									});
									
									let downloadLink = document.createElement('a');
									downloadLink.target = '_blank';
									downloadLink.download = 'abstracts preview.pdf';

									let URL = window.URL || window.webkitURL;
									let downloadUrl = URL.createObjectURL(blob);

									downloadLink.href = downloadUrl;

									document.body.append(downloadLink);

									downloadLink.click();
									downloadLink.remove();
								})
								.finally(() => {
									this.loading = false
								})
						}
					}"
				>
					{{ __('my/events/theses.edit.preview') }}
					<x-loader class="tw-w-5 tw-h-4" />
				</button>
            </div>
        </div>
        <div class="form__row">
            <div class="form__btns">
                <button class="form__button button button_primary" :disabled="form.processing" type="submit">
					{{ __('my/events/theses.edit.save_abstracts') }}
					<x-loader class="tw-w-5 tw-h-4" />
				</button>
            </div>
        </div>

		<template x-if="form.hasErrors">
			<div class="form__row">
				<div class="form__error">{{ __('my/events/theses.edit.mistakes_in_form') }}:</div>
				<ul>
					<template x-for="error in form.errors">
						<li class="form__error" x-text="error"></li>
					</template>
				</ul>
			</div>
		</template>

    </form>
@endsection
