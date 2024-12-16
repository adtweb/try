@extends('layouts.app')

@section('title', 'Редактирование расписания')

@section('head_scripts')
	@vite(['resources/js/sortable.js'])
@endsection

@section('content')
    <main class="page page_edit _single-thesis">
        <section class="edit">
            <div class="edit__container">
                <div class="edit__wrapper">
                    <aside class="edit__aside aside">
                        <a href="{{ route('schedule.index', $conference->slug) }}"
                            class="aside__back _icon-arrow-back"></a>
                    </aside>
                    <div class="edit-content">
                        <nav class="edit-content__breadcrumbs breadcrumbs">
                            <ul class="breadcrumbs__list">
                                <li class="breadcrumbs__item">
                                    <a href="{{ route('events.organization-index') }}" class="breadcrumbs__link">
                                        <span>{{ __('my/events/personal.schedule.breadcrumbs.1') }}</span>
                                    </a>
                                </li>
                                <li class="breadcrumbs__item">
                                    <a href="{{ route('conference.show', $conference->slug) }}" class="breadcrumbs__link">
                                        <span>{{ $conference->{'title_'.loc()} }}</span>
                                    </a>
                                </li>
                                <li class="breadcrumbs__item">
                                    <a href="{{ route('schedule.index', $conference->slug) }}" class="breadcrumbs__link">
                                        <span title="{{ __('my/events/personal.schedule.breadcrumbs.2') }}">{{ __('my/events/personal.schedule.breadcrumbs.2') }}</span>
                                    </a>
                                </li>
                                <li class="breadcrumbs__item">
									<span class="breadcrumbs__current">{{ $schedule->date->translatedFormat('d F Y') }}</span>
									@isset ($section)
										<span class="breadcrumbs__current">({{ $section->slug }})</span>
									@endif
                                </li>
                            </ul>
                        </nav>

						<div x-data="schedule">
							<div class="tw-flex tw-justify-between tw-mb-6">
								<div class="tw-gap-2 tw-grid tw-grid-cols-2">
									<div class="tw-p-2 tw-flex tw-items-center tw-justify-center tw-rounded tw-bg-[#8756c966] tw-text-[#000]">
										{{ __('my/events/personal.schedule.oral') }}
									</div>
									<div class="tw-p-2 tw-flex tw-items-center tw-justify-center tw-rounded tw-text-[#000] tw-bg-[#55f289]">
										{{ __('my/events/personal.schedule.stand') }}
									</div>
									<div class="tw-p-2 tw-flex tw-items-center tw-justify-center tw-rounded tw-text-[#000] tw-bg-[#fff]">
										{{ __('my/events/personal.schedule.no_prefer') }}
									</div>
								</div>
								<div class="">
									<div class="tw-flex tw-gap-1 tw-justify-end tw-items-center tw-mb-2">
										<span>{{ __('my/events/personal.schedule.sort') }}:</span>
										<button 
											class="button" 
											:class="sort === 'id' ? 'button_primary' : 'button_outline'"
											@click="sort = 'id'"
										>{{ __('my/events/personal.schedule.sort_by_id') }}</button>
										<button 
											class="button" 
											:class="sort === 'reporter' ? 'button_primary' : 'button_outline'"
											@click="sort = 'reporter'"
										>{{ __('my/events/personal.schedule.sort_by_reporter') }}</button>
									</div>
									<div class="tw-flex tw-gap-1 tw-justify-end tw-items-center">
										<span>{{ __('my/events/personal.schedule.filters') }}:</span>
										<button 
											class="button" 
											:class="filter === 'all' ? 'button_primary' : 'button_outline'"
											@click="filter = 'all'"
										>
											{{ __('my/events/personal.schedule.filters_all') }}
											<span x-text="`(${unassignedTheses.length})`"></span>
										</button>
										<button 
											class="button" 
											:class="filter === 'solicited' ? 'button_primary' : 'button_outline'"
											@click="filter = 'solicited'"
										>
											{{ __('my/events/personal.schedule.filters_solicited') }}
											<span x-text="'('+solicitedFilterCount()+')'"></span>
										</button>
										<button 
											class="button" 
											:class="filter === 'oral' ? 'button_primary' : 'button_outline'"
											@click="filter = 'oral'"
										>
											{{ __('my/events/personal.schedule.filters_oral') }}
											<span x-text="'('+oralFilterCount()+')'"></span>
										</button>
										<button 
											class="button" 
											:class="filter === 'stand' ? 'button_primary' : 'button_outline'"
											@click="filter = 'stand'"
										>
											{{ __('my/events/personal.schedule.filters_stand') }}
											<span x-text="'('+standFilterCount()+')'"></span>
										</button>
									</div>
								</div>
							</div>
							<div class="gap-2 tw-flex tw-gap-16 tw-min-h-[200px]">
								<div class="tw-relative tw-basis-1/2 tw-flex tw-flex-col tw-gap-1 tw-bg-[#cccccc45] tw-rounded tw-max-h-[500px] tw-overflow-y-auto tw-overflow-x-hidden">
									<div class="tw-h-full tw-overflow-y-auto">
										<div class="tw-text-center tw-p-1" x-text="DateTime.fromISO(schedule.start_time).toFormat('HH:mm')"></div>
										<div class="tw-flex-shrink-0 tw-h-[calc(100%-44px-0.5rem)] tw-overflow-x-hidden" x-ref="sheduleItemsWrap" id="items_wrap">
											<div class="tw-flex tw-flex-col tw-gap-1 tw-max-w-full tw-h-full" x-ref="items" id="items" data-type="items">
												<template x-for="item in schedule.schedule_items">
													<div class="tw-rounded tw-p-2 tw-group"
														:class="itemClass(item)"
														:data-id="item.id" 
														:data-thesis_id="item.thesis_id"
														:data-thesis_slug="item.thesis?.thesis_id" 
														:data-title="item.title"
														:data-start="DateTime.fromISO(item.time_start).toFormat('HH mm')"
														:data-end="DateTime.fromISO(item.time_end).toFormat('HH mm')"
														:data-duration="DateTime.fromISO(item.time_end).diff(DateTime.fromISO(item.time_start), 'minutes').toObject().minutes"
														:data-standart="+item.is_standart"
														:data-type="item.type"
													>
														<div class="tw-flex-col tw-flex tw-gap-1">
															<template x-if="item.thesis">
																<div class="tw-flex-col tw-flex tw-gap-2 tw-items-start tw-mb-2">
																	<span class="tw-whitespace-nowrap" x-text="item.thesis?.thesis_id"></span>
																	<span x-html="item.thesis?.title"></span>
																	<span class="tw-whitespace-nowrap" x-text="getReporterName(item.thesis)"></span>
																</div>
															</template>
															<template x-if="!item.thesis">
																<span x-html="item.title"></span>
															</template>
															<div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
																<div>
																	<div class="" x-show="+DateTime.fromISO(item.time_start) !== +DateTime.fromISO(item.time_end)">
																		<span x-text="DateTime.fromISO(item.time_start).toFormat('HH:mm')"></span>
																		<span>-</span>
																		<span x-text="DateTime.fromISO(item.time_end).toFormat('HH:mm')"></span>
																		<span x-text="'(' + DateTime.fromISO(item.time_end).diff(DateTime.fromISO(item.time_start), 'minutes').toObject().minutes + ' мин.)'"></span>
																	</div>
																</div>
																<div class="tw-flex tw-gap-2 tw-opacity-0 group-hover:tw-opacity-100 tw-transition">
																	<button x-show="!item.is_standart" @click="addTag()">
																		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-4 tw-h-4">
																			<path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
																		</svg>
																	</button>
																	<button @click="editItem()">
																		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" class="tw-w-4 tw-h-4">
																			<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
																		</svg>
																	</button>
																	<button @click="deleteItem(item)">
																		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-4 tw-h-4">
																			<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
																		</svg>
																	</button>
																</div>
															</div>
															<div class="tw-font-bold tw-text-[#d14442] tw-text-center" x-show="item.thesis?.solicited_talk">{{ __('my/events/personal.schedule.solicited_talk') }}</div>
															<div class="tw-flex tw-items-center tw-justify-start tw-gap-2">
																<template x-for="tag in item.schedule_item_tags">
																	<div class="tw-rounded tw-p-2" :style="'background-color:'+tag.color+';'" x-text="tag.title_en"></div>
																</template>
															</div>
														</div>
													</div>
												</template>
											</div>
										</div>
										<div class="tw-text-center tw-p-1" x-text="DateTime.fromISO(schedule.end_time).toFormat('HH:mm')"></div>
									</div>

									<div class="tw-absolute tw-top-0 tw-bottom-0 tw-left-0 tw-right-0 tw-bg-[#ffffff56] tw-flex tw-items-center tw-justify-center" 
										x-show="block" 
										x-transition.opacity
									>
										<svg class="tw-size-12" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 150'><path fill='none' stroke='currentColor' stroke-width='19' stroke-linecap='round' stroke-dasharray='300 385' stroke-dashoffset='0' d='M275 75c0 31-27 50-50 50-58 0-92-100-150-100-28 0-50 22-50 50s23 50 50 50c58 0 92-100 150-100 24 0 50 19 50 50Z'><animate attributeName='stroke-dashoffset' calcMode='spline' dur='2' values='685;-685' keySplines='0 0 1 1' repeatCount='indefinite'></animate></path></svg>
									</div>
								</div>
								<div class="tw-basis-1/2 tw-max-h-[500px] tw-overflow-auto">
									<div class="tw-flex tw-flex-col tw-gap-1 tw-mb-4 tw-bg-[#cccccc45] tw-rounded" 
										data-type="stand"
										x-ref="stand" 
									>
										<div class="tw-bg-[#1256c966] tw-rounded tw-p-2 tw-cursor-grab active:tw-cursor-grabbing" data-title="Coffee break" data-duration="30" data-type="break">
											Coffee break
										</div>
										<div class="tw-bg-[#1256c966] tw-rounded tw-p-2 tw-cursor-grab active:tw-cursor-grabbing" data-title="Lunch" data-duration="60" data-type="break">
											Lunch
										</div>
										<div class="tw-bg-[#1256c966] tw-rounded tw-p-2 tw-cursor-grab active:tw-cursor-grabbing" data-title="" data-type="custom">
											Custom
										</div>
									</div>
									<div class="tw-flex tw-flex-col tw-gap-1 tw-bg-[#cccccc45] tw-rounded tw-max-h-[384px] tw-min-h-[384px] tw-overflow-auto tw-pb-1" x-ref="theses" data-type="theses">
										<template x-for="thesis in filteredUnassignedTheses">
											<div class="tw-bg-[#8756c966] tw-rounded tw-p-2 tw-cursor-grab active:tw-cursor-grabbing"
												:class="thesisClass(thesis)"
												:data-thesis_id="thesis.id" 
												:data-thesis_slug="thesis.thesis_id" 
												:data-title="thesis.title"
												:data-duration="thesis.report_form === 'stand' ? 0 : 20"
												data-type="report"
											>
												<div class="tw-flex-col tw-flex tw-gap-2 tw-items-start tw-mb-2">
													<span class="tw-whitespace-nowrap" x-text="thesis.thesis_id"></span>
													<span x-html="thesis.title"></span>
													<span class="tw-whitespace-nowrap" x-text="getReporterName(thesis)"></span>
												</div>
												<div class="tw-font-bold tw-text-[#d14442] tw-text-center" x-show="thesis.solicited_talk">{{ __('my/events/personal.schedule.solicited_talk') }}</div>
											</div>
										</template>
									</div>
								</div>
							</div>
							<x-popup title="Редактирование" id="edit_slot">
								<form @submit.prevent="submitUpdate">
									<template x-if="editingElement?.dataset.standart == '1'">
										<div class="tw-mb-3">
											<div class="form__line">
												<input type="text" name="title" class="input" :value="editingElement?.dataset.title" required>
											</div>
											<template x-if="editingErrors.title">
												<div class="form__error tw-mb-3" x-text="editingErrors.title"></div>
											</template>
										</div>
									</template>
									<div class="form__line tw-flex tw-atems-center tw-gap-3 tw-mb-[.625rem]">
										<label class="form__label tw-whitespace-nowrap tw-mb-0 tw-flex tw-items-center">{{ __('my/events/personal.schedule.duration') }}</label>
										<input type="number" name="duration" class="input tw-text-center" :value="editingElement?.dataset.duration" min="0" step="1" required>
									</div>
									<template x-if="editingErrors.duration">
										<div class="form__error tw-mb-3" x-text="editingErrors.duration"></div>
									</template>
									<template x-if="!editingElement?.previousElementSibling?.dataset.end">
										<div class="tw-mb-3">
											<div class="form__line tw-flex tw-atems-center tw-gap-3">
												<label class="form__label tw-whitespace-nowrap tw-mb-0 tw-flex tw-items-center">{{ __('my/events/personal.schedule.start_time') }}</label>
												<input type="time" name="start" class="input tw-text-center" :value="editingElement?.dataset.start.replace(' ', ':')" required>
											</div>
											<template x-if="editingErrors.start">
												<div class="form__error tw-mb-3" x-text="editingErrors.start"></div>
											</template>
										</div>
									</template>
									<div class="form__line">
										<button class="button button-primary">{{ __('my/events/personal.schedule.save') }}</button>
									</div>
								</form>
							</x-popup>
							<x-popup title="{{ __('my/events/personal.schedule.popup.title') }}" id="add_tag">
								<form @submit.prevent="storeTag" x-data="{
									selectedTag: null,
									tags: [
										{
											title_ru: 'Онлайн',
											title_en: 'Online',
											color: '#309007',
										},
										{
											title_ru: 'Отменен',
											title_en: 'Canceled',
											color: '#CA1111',
										},
									],
									storeTag() {
										let data = new FormData(this.$event.target)

										axios
											.post(route('scheduleItemTag.store', [this.conference.slug, this.section.id]), {
												'schedule_item_id': this.editingElement.dataset.id,
												'title_ru': data.get('title_ru'),
												'title_en': data.get('title_en'),
												'color': data.get('color'),
											})
											.then(response => {
												this.editingScheduleItem
													.schedule_item_tags.push(response.data)
											})
									},
									removeTag(key, tag) {
										axios
											.delete(route('scheduleItemTag.destroy', [this.conference.slug, this.section.id, tag.id]))
											.then(response => {
												this.editingScheduleItem.schedule_item_tags.splice(key, 1)
											}).catch(error => this.$store.toasts.handleResponseError(error))
									}
								}">
									<input type="hidden" name="title_ru" :value="selectedTag?.title_ru">
									<input type="hidden" name="title_en" :value="selectedTag?.title_en">
									<input type="hidden" name="color" :value="selectedTag?.color">
									<div class="form__line tw-flex tw-justify-center tw-gap-2">
										<template x-for="tag in tags">
											<button type="button" 
												class="button"
												:class="selectedTag === tag ? 'button_primary' : 'button_outline'" 
												x-text="tag.title_en" 
												@click="selectedTag = tag"
											></button>
										</template>
									</div>
									<div class="form__line tw-mb-5">
										<button class="button button-primary" :disabled="!selectedTag" @click="show = false">{{ __('my/events/personal.schedule.popup.add') }}</button>
									</div>
									<div class="form__line ">
										<div class="tw-mb-3" x-show="editingScheduleItem?.schedule_item_tags.length > 0">{{ __('my/events/personal.schedule.popup.h1') }}:</div>
										<div class="tw-flex tw-justify-start tw-gap-2 tw-flex-wrap">
											<template x-for="tag, key in editingScheduleItem?.schedule_item_tags">
												<div class="tw-flex tw-justify-center tw-items-center tw-p-1 tw-gap-1">
													<button type="button" class="_icon-close" @click="removeTag(key, tag)"></button>
													<div class="" x-text="tag.title_en" :style="'color: ' + tag.color + ';'"></div>
												</div>
											</template>
										</div>
									</div>
								</form>
							</x-popup>
						</div>


						<script>
							document.addEventListener('alpine:init', () => {
								Alpine.data('schedule', () => ({
									conference: @json($conference),
									schedule: @json($schedule),
									unassignedTheses: @json($unassignedTheses),
									filteredUnassignedTheses: @json($unassignedTheses),
									section: @json($section),
									editingElement: null,
									editingScheduleItem: null,
									editingErrors: {},
									sort: 'id',
									filter: 'all',
									block: false,

									init() {
										new Sortable(this.$refs.stand, {
											group: {
												name: 'shared',
												pull: 'clone',
												put: false,
											},
											animation: 150,
											sort: false,
										});

										new Sortable(this.$refs.theses, {
											group: {
												name: 'shared',
											},
											animation: 150,
											sort: false,
										});

										new Sortable(this.$refs.items, {
											group: {
												name: 'shared',
											},
											animation: 150,
											onAdd: async (e) => {
												this.addItem(e)
												let scrollTop = document.getElementById('items_wrap').scrollTop
												
												try {
													let response = await this.saveSchedule()
													this.redrawSchedule(response, scrollTop)
													
													e.item.remove()

													let thesis = this.unassignedTheses.find(el => el.id == e.item.dataset.thesis_id)
													let index = this.unassignedTheses.indexOf(thesis)
													if (index > -1) {
														this.unassignedTheses.splice(index, 1)
													}
													this.filterTheses(this.filter)
												} catch (error) {
													e.item.remove()
												}
											},
											onRemove: e => {
												let itemId = e.item.dataset.id
												let item = this.schedule.schedule_items.find(el => el.id == itemId)
												if (item) {
													this.deleteItem(item)
													e.item.remove()
												}
											},
											onUpdate: async (e) => {
												this.updateList(e)
												let scrollTop = document.getElementById('items_wrap').scrollTop
												let response = await this.saveSchedule()
												this.redrawSchedule(response, scrollTop)
											}
										});

										this.$watch('sort', (val) => this.sortBy(val))
										this.$watch('filter', (val) => this.filterTheses(val))
									},
									addItem(evt) {
										let start, duration, title

										if (evt.item.dataset.title === '') {
											do {
												title = prompt('Введите название блока', 'Стендовые доклады')

												if (title === null) return

												evt.item.dataset.title = title
											} while (title.trim().length === 0);
										}

										start = evt.item.previousElementSibling?.dataset.end

										if (start === undefined) {
											start = this.promptStart()
										}

										if (start === null) {
											this.reset()
											return
										}

										evt.item.dataset.start = start

										if (evt.item.dataset.duration === undefined) {
											do {
												duration = prompt('Введите продолжительность в минутах', '60')
												
												if (duration === null) return

												if (!this.checkDuration()) {
													duration = ''
												}

											} while (duration.match(/^\d{1,3}$/) === null);
										} else {
											duration = evt.item.dataset.duration
										}

										evt.item.dataset.duration = duration
										evt.item.dataset.standart = +(evt.from.dataset.type === 'stand')
									},
									updateList(evt) {
										start = evt.item.previousElementSibling?.dataset.end

										if (start === undefined) {
											start = this.promptStart()
										}

										if (start === null) {
											this.reset()
											return
										}

										evt.item.dataset.start = start
									},
									async saveSchedule() {
										if(!this.checkDuration()) {
											this.reset()
											throw 'Duration is too big'
										}

										this.block = true

										let items = []
										
										Array.from(document.getElementById('items').children).forEach(el => {
											if (!el.dataset.start) return

											items.push({
												id: el.dataset.id,
												thesis_id: el.dataset.thesis_id,
												start: el.dataset.start,
												duration: el.dataset.duration,
												title: el.dataset.title,
												is_standart: el.dataset.standart,
												type: el.dataset.type,
											})
										})
										
										const response = await axios
											.put(route('schedule.massUpdate', this.conference.slug), {
												items,
												schedule_id: this.schedule.id,
												section_id: this.section.id,
											})
											.catch(err => this.$store.toasts.handleResponseError(err))
										
										return response
									},
									redrawSchedule(response, scrollTop) {
										this.schedule.schedule_items = []
										this.$nextTick(() => {
											this.schedule.schedule_items = response.data
											setTimeout(() => {
												document.getElementById('items_wrap').scrollTop = scrollTop
											}, 50);
											this.block = false
										})
									},
									reset() {
										this.filteredUnassignedTheses = []
										this.$nextTick(() => {
											this.filteredUnassignedTheses = this.unassignedTheses
											this.filterTheses(this.filter)
										})
									},
									getReporterName(thesis) {
										reporterId = thesis.reporter.id

										if (thesis.authors[reporterId]) {
											return `${thesis.authors[reporterId].surname_en} ${thesis.authors[reporterId].name_en}`
										}

										return '';
									},
									sortBy(val) {
										if (val === 'id') {
											this.filteredUnassignedTheses.sort((a, b) => (a.thesis_id > b.thesis_id) ? 1 : ((b.thesis_id > a.thesis_id) ? -1 : 0))
											return
										}

										if (val === 'reporter') {
											this.filteredUnassignedTheses.sort((a, b) => {
												if (this.getReporterName(a) > this.getReporterName(b)) {
													return 1
												}
												
												if (this.getReporterName(b) > this.getReporterName(a)) {
													return -1
												}
												
												return 0
											})
										}
									},
									filterTheses(val) {
										this.filteredUnassignedTheses = []
										this.$nextTick(() => {
											this.filterBy(val)
											this.sortBy(this.sort)
										})
									},
									filterBy(val) {
										if (val === 'all') {
											this.filteredUnassignedTheses = this.unassignedTheses
											return
										}

										if (val === 'solicited') {
											this.filteredUnassignedTheses = this.unassignedTheses.filter(el => {
												return el.solicited_talk
											})
											return
										}

										if (val === 'oral') {
											this.filteredUnassignedTheses = this.unassignedTheses.filter(el => {
												return el.report_form === 'oral'
											})
											return
										}

										if (val === 'stand') {
											this.filteredUnassignedTheses = this.unassignedTheses.filter(el => {
												return el.report_form === 'stand'
											})
											return
										}
									},
									solicitedFilterCount() {
										return this.unassignedTheses.filter(el => el.solicited_talk).length
									},
									oralFilterCount() {
										return this.unassignedTheses.filter(el => el.report_form === 'oral').length
									},
									standFilterCount() {
										return this.unassignedTheses.filter(el => el.report_form === 'stand').length
									},
									async deleteItem(item) {
										if (!item.is_standart) {
											this.unassignedTheses.push(item.thesis)
											this.filterTheses(this.filter)
										}

										if (this.$event) {
											this.$event.target.closest('[data-title]').remove()
										}
										let scrollTop = document.getElementById('items_wrap').scrollTop
										let response = await this.saveSchedule()
										this.redrawSchedule(response, scrollTop)
									},
									editItem() {
										this.editingElement = this.$event.target.closest('[data-title]')
										this.$dispatch('popup', 'edit_slot')
									},
									addTag() {
										this.editingElement = this.$event.target.closest('[data-title]')
										this.editingScheduleItem = this.schedule.schedule_items
											.find(el => el.id == this.editingElement.dataset.id)
										this.$dispatch('popup', 'add_tag')
									},
									async submitUpdate(e) {
										let data = new FormData(e.target)

										let cache = {
											title: this.editingElement.dataset.title,
											duration: this.editingElement.dataset.duration,
											start: this.editingElement.dataset.start,
										}

										if (data.has('title')) {
											this.editingElement.dataset.title = data.get('title')
										}
										if (data.has('duration')) {
											this.editingElement.dataset.duration = data.get('duration')
										}
										if (data.has('start')) {
											this.editingElement.dataset.start = data.get('start').replace(':', ' ')
										}

										this.editingErrors = {}

										if (data.get('start') < DateTime.fromISO(this.schedule.start_time).toFormat('HH:mm')) {
											this.refreshElement(cache)
											this.editingErrors.start = 'Время начала не должно быть раньше ' + DateTime.fromISO(this.schedule.start_time).toFormat('HH:mm')
											return
										}

										if (!this.checkDuration()) {
											this.refreshElement(cache)
											this.editingErrors.duration = 'Слишком большая продолжительность. Не входит в расписание'
											return
										}

										if (data.has('title') && data.get('title').length > 255) {
											this.refreshElement(cache)
											this.editingErrors.title = 'Длина названия не может превышать 255 символов'
											return
										}

										this.$dispatch('popup-close', 'edit_slot')
										this.editingErrors = {}
										let scrollTop = document.getElementById('items_wrap').scrollTop
										let response = await this.saveSchedule()
										this.redrawSchedule(response, scrollTop)
									},
									itemClass(item) {
										return {
											'tw-bg-[#1256c966]': item.is_standart,
											'tw-bg-[#8756c966]': item.thesis?.report_form === 'oral',
											'tw-bg-[#55f289]': item.thesis?.report_form === 'stand',
											'tw-bg-[#ffffff]': item.thesis?.report_form === 'any',
										}
									},
									thesisClass(thesis) {
										return {
											'tw-bg-[#8756c966]': thesis?.report_form === 'oral',
											'tw-bg-[#55f289]': thesis?.report_form === 'stand',
											'tw-bg-[#ffffff]': thesis?.report_form === 'any',
										}
									},
									promptStart() {
										do {
											start = prompt(
												'Введите время начала. Часы и минуты нужно разделить пробелом. Самое раннее время: ' + DateTime.fromISO(this.schedule.start_time).toFormat('HH:mm'), 
												DateTime.fromISO(this.schedule.start_time).toFormat('HH mm')
											)
											
											if (start === null) {
												break
											}

											let hours = +start.split(' ')[0]
											let minutes = +start.split(' ')[1]

											if (hours < +DateTime.fromISO(this.schedule.start_time).toFormat('HH')) {
												start = ''
											} else if (hours === +DateTime.fromISO(this.schedule.start_time).toFormat('HH')) {
												if (minutes < +DateTime.fromISO(this.schedule.start_time).toFormat('mm')) {
													start = ''
												}
											}

										} while (start.match(/^\d{1,2} \d{2}$/) === null);

										return start
									},
									checkDuration() {
										if (document.getElementById('items').children.length < 2) {
											return true;
										}
										
										let start = document.getElementById('items').children[1].dataset.start.split(' ')
										let startDt = DateTime.fromObject({minute: start[1], hour: start[0] })

										for (let item of document.getElementById('items').children) {
											if (item.tagName === 'TEMPLATE' || item.tagName === 'template') continue

											startDt = startDt.plus({minutes: item.dataset.duration})
										}

										let end = DateTime.fromISO(this.schedule.end_time)
										let endDt = DateTime.fromObject({
											hour: end.hour,
											minute: end.minute, 
										})
										
										return endDt >= startDt
									},
									refreshElement(cache) {
										this.editingElement.dataset.title = cache.title
										this.editingElement.dataset.duration = cache.duration
										this.editingElement.dataset.start = cache.start
									},
								}))
							})
						</script>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
