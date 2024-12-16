@extends('layouts.conference-lk')

@section('title', __('my/events/personal.schedules.title'))

@section('content')
    @php
        $lang = $conference->abstracts_lang->value;
    @endphp
	<x-my.breadcrumbs class="tw-mt-3" :items="[
		route('events.organization-index') => __('my/events/personal.schedules.breadcrumbs.1'),
		route('conference.show', $conference->slug) => $conference->{'title_'.loc()},
		'#' => __('my/events/personal.schedules.breadcrumbs.2')
	]" />
    <h1 class="edit-content__title">{{ __('my/events/personal.schedules.h1') }}</h1>

    <div class="" x-data="schedules">
        <div class="mb-3 tw-flex tw-gap-2 tw-mb-8 tw-flex-wrap">
            @foreach (period($conference->start_date, $conference->end_date) as $day)
                <button class="button" :class="date === '{{ $day->toISOString() }}' ? 'button_primary' : 'button_outline'"
                    @click="date = '{{ $day->toISOString() }}'">{{ $day->format('d.m.Y') }}</button>
            @endforeach
        </div>

        <template x-if="!activeSchedule">
            <div class="">
                <div class="tw-mb-3">{{ __('my/events/personal.schedules.no-schedule') }}</div>
                @can('createSchedule', $conference)
                    <button class="button button-primary" @click="$dispatch('popup', 'create_schedule')">{{ __('my/events/personal.schedules.create_schedule') }}</button>
                @endcan
            </div>
        </template>

        <template x-if="activeSchedule">
			<div class="">
				<div class="tw-mb-4 tw-flex tw-justify-between tw-items-center tw-gap-2">
					<div>
						@can('update', $conference)
							<button class="button button_outline" 
								@click="publishSchedule" 
								x-text="conference.schedule_is_published ? '{{ __('my/events/personal.schedules.published') }}' : '{{ __('my/events/personal.schedules.publish') }}'" 
								:disabled="conference.schedule_is_published"
							></button>
						@endcan
					</div>
					<div class="tw-flex tw-gap-2 tw-items-center">
						<a class="button button_outline" href="{{ route('conference.schedule', $conference->slug) }}" target="_blank">{{ __('my/events/personal.schedules.preview') }}</a>
						<a href="{{ route('pdf.schedule.download', $conference->slug) }}">
							<svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="tw-w-7 tw-h-7">
								<path
									d="M9.1445 16.6737H10.492V13.1737H12.6445C13.0283 13.1737 13.3486 13.0454 13.6053 12.7887C13.8619 12.5321 13.9903 12.2112 13.9903 11.8263V9.67375C13.9903 9.28875 13.8619 8.96792 13.6053 8.71125C13.3486 8.45458 13.0278 8.32625 12.6427 8.32625H9.14275L9.1445 16.6737ZM10.492 11.8263V9.67375H12.6445V11.8263H10.492ZM15.8085 16.6737H19.1737C19.5564 16.6737 19.8767 16.5454 20.1345 16.2887C20.3912 16.0321 20.5195 15.7112 20.5195 15.3263V9.67375C20.5195 9.28875 20.3912 8.96792 20.1345 8.71125C19.8778 8.45458 19.557 8.32625 19.172 8.32625H15.8085V16.6737ZM17.1542 15.3263V9.67375H19.1737V15.3263H17.1542ZM22.6737 16.6737H24.0195V13.1737H26.4415V11.8263H24.0195V9.67375H26.4415V8.32625H22.6737V16.6737ZM8.20125 24.75C7.39625 24.75 6.72425 24.4805 6.18525 23.9415C5.64508 23.4013 5.375 22.7288 5.375 21.9237V3.07625C5.375 2.27125 5.64508 1.59925 6.18525 1.06025C6.72425 0.520083 7.39625 0.25 8.20125 0.25H27.0487C27.8538 0.25 28.5258 0.520083 29.0648 1.06025C29.6049 1.59925 29.875 2.27125 29.875 3.07625V21.9237C29.875 22.7288 29.6055 23.4013 29.0665 23.9415C28.5263 24.4805 27.8538 24.75 27.0487 24.75H8.20125ZM8.20125 23H27.0487C27.3171 23 27.5638 22.888 27.789 22.664C28.013 22.4388 28.125 22.1921 28.125 21.9237V3.07625C28.125 2.80792 28.013 2.56117 27.789 2.336C27.5638 2.112 27.3171 2 27.0487 2H8.20125C7.93292 2 7.68617 2.112 7.461 2.336C7.237 2.56117 7.125 2.80792 7.125 3.07625V21.9237C7.125 22.1921 7.237 22.4388 7.461 22.664C7.68617 22.888 7.93292 23 8.20125 23ZM2.95125 30C2.14625 30 1.47425 29.7305 0.93525 29.1915C0.395083 28.6513 0.125 27.9788 0.125 27.1737V6.57625H1.875V27.1737C1.875 27.4421 1.987 27.6888 2.211 27.914C2.43617 28.138 2.68292 28.25 2.95125 28.25H23.5487V30H2.95125Z"
									fill="#E25553" />
							</svg>
						</a>
					</div>
				</div>
	
				<div class="accordion">
					<template x-for="section in conference.sections">
						<div class="accordion-item" 
							x-data="{
								scheduleItems: [],

								init() {
									this.$watch('date', (val) => this.filterScheduleItems())

									this.filterScheduleItems()
								},
								filterScheduleItems() {
									this.scheduleItems = this.activeSchedule?.schedule_items.filter(el => {
										return el.section_id == section.id
									})
								},
								notifyAboutChanges() {
									if (!confirm('{{ __('my/events/personal.schedules.send_notification_confirm') }}')) {
										return
									}

									axios
										.post(route('schedule.section.send-changes', [conference.slug, activeSchedule?.id ?? 0, section.id]))
										.then(response => alert('{{ __('my/events/personal.schedules.send_notification_success') }}'))
										.catch(error => {
											this.$store.toasts.handleResponseError(error)
										})
								},
							}"
						>
							<input :id="'accordion-trigger-' + section.id" class="accordion-trigger-input"
								type="checkbox">
							<label class="accordion-trigger tw-flex tw-justify-between tw-gap-2 tw-items-center" :for="'accordion-trigger-' + section.id">
								<div class="tw-flex tw-justify-between tw-w-full tw-mr-6">
									<div class="">
										<span x-text="section.slug"></span>
										<span>|</span>
										<span x-text="section.title_{{ loc() }}"></span>
									</div>
									<div x-show="unassignedTheses[section.id] > 0" class="tw-text-[#e25553]" x-text="'{{ __('my/events/personal.schedules.unassigned_theses') }}: ' + unassignedTheses[section.id]"></div>
								</div>
							</label>
							<div class="accordion-animation-wrapper">
								<div class="accordion-animation">
									<div class="accordion-transform-wrapper">
										<div class="accordion-content">
											<div class="tw-flex tw-justify-between" x-show="moderableSectionsIds.includes(section.id)">
												<a class="button button-primary" 
													:href="route('schedule.section.edit', [conference.slug, activeSchedule?.id ?? 0, section.id])"
												>{{ __('my/events/personal.schedules.edit_schedule') }}</a>
												<button class="button button_outline" @click="notifyAboutChanges">{{ __('my/events/personal.schedules.send_notification') }}</button>
											</div>
											<div class="gap-2 tw-flex tw-gap-2 tw-min-h-[200px]">
												<div class="tw-basis-full tw-flex tw-flex-col tw-gap-1 tw-bg-[#cccccc45] tw-rounded tw-max-h-[500px] tw-overflow-auto" 
													x-ref="items" data-type="items">
													<template x-for="item in scheduleItems">
														<div class="tw-rounded tw-p-2"
															:class="itemClass(item)"
														>
															<div class="tw-flex-col tw-flex tw-gap-1">
																<template x-if="item.thesis">
																	<div class="tw-flex-col tw-flex tw-gap-2 tw-items-start tw-mb-2">
																		<span class="tw-whitespace-nowrap" x-text="item.thesis.thesis_id"></span>
																		<span x-html="item.thesis.title"></span>
																		<span class="tw-whitespace-nowrap" x-text="getReporterName(item.thesis)"></span>
																	</div>
																</template>
																<template x-if="!item.thesis">
																	<span x-html="item.title"></span>
																</template>
																<div class="tw-mb-2" x-show="+DateTime.fromISO(item.time_start) !== +DateTime.fromISO(item.time_end)">
																	<span x-text="DateTime.fromISO(item.time_start).toFormat('HH:mm')"></span>
																	<span>-</span>
																	<span x-text="DateTime.fromISO(item.time_end).toFormat('HH:mm')"></span>
																	<span x-text="'(' + DateTime.fromISO(item.time_end).diff(DateTime.fromISO(item.time_start), 'minutes').toObject().minutes + ' мин.)'"></span>
																</div>
																<div class="tw-font-bold tw-text-[#d14442] tw-text-center" x-show="item.thesis?.solicited_talk">{{ __('my/events/personal.schedules.solicited_talk') }}</div>
																<div class="tw-flex tw-items-center tw-justify-start tw-gap-2">
																	<template x-for="tag in item.schedule_item_tags">
																		<div class="tw-rounded tw-p-2" :style="'background-color:'+tag.color+';'" x-text="tag.title_en"></div>
																	</template>
																</div>
															</div>
														</div>
													</template>
													<template x-if="scheduleItems?.length === 0">
														<div class="tw-py-5 tw-text-center">{{ __('my/events/personal.schedules.empty_schedule') }}</div>
													</template>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</template>
				</div>
			</div>
        </template>

		<x-popup id="create_schedule" title="{{ __('my/events/personal.schedules.popup.title') }}">
			<form class="tw-gap-2 tw-flex tw-flex-col tw-text-[16px]" @submit.prevent="createSchedule();show=false">
				<div>
					{{ __('my/events/personal.schedules.popup.date') }}:
					<span x-text="DateTime.fromISO(date).toLocaleString(DateTime.DATE_FULL)"></span>
				</div>
				<div class="tw-flex tw-gap-2 tw-justify-center">
					<label>
						{{ __('my/events/personal.schedules.popup.start_time') }}
						<input type="time" x-model="startTime">
					</label>
				</div>
				<div class="tw-flex tw-gap-2 tw-justify-center">
					<label>
						{{ __('my/events/personal.schedules.popup.end_time') }}
						<input type="time" x-model="endTime">
					</label>
				</div>

				<button class="button button-primary">{{ __('my/events/personal.schedules.popup.btn') }}</button>
			</form>
		</x-popup>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('schedules', () => ({
                conference: @json($conference),
				startTime: null,
				endTime: null,
                date: '{{ $conference->start_date->toISOString() }}',
                schedules: @json($conference->schedules),
                activeSchedule: null,
				moderableSectionsIds: @json($moderableSectionsIds),
				unassignedTheses: @json($unassignedTheses),

                init() {
                    this.$watch('date', (val) => this.findSchedule())

                    this.findSchedule()
                },
                createSchedule() {
                    axios
                        .post(route('schedule.store', this.conference.slug), {
                            date: this.date,
                            start_time: this.startTime,
                            end_time: this.endTime,
                        })
                        .then(response => {
                            this.schedules.push(response.data)
                            this.findSchedule()
                        })
                },
                findSchedule() {
                    this.activeSchedule = this.schedules.find(el => el.date == this.date)
                },
				getReporterName(thesis) {
					reporterId = thesis.reporter.id

					return `${thesis.authors[reporterId].name_en} ${thesis.authors[reporterId].surname_en}`
				},
				itemClass(item) {
					return {
						'tw-bg-[#1256c966]': item.is_standart,
						'tw-bg-[#8756c966]': item.thesis?.report_form === 'oral',
						'tw-bg-[#55f289]': item.thesis?.report_form === 'stand',
						'tw-bg-[#ffffff]': item.thesis?.report_form === 'any',
					}
				},
				publishSchedule() {
					if (confirm(`{{ __('my/events/personal.schedules.publish_confirm') }}`)) {
						axios
							.post(route('conference.publishSchedule', this.conference.slug))
							.then(response => {
								this.conference.schedule_is_published = true
							})
							.catch(error => {
								this.$store.toasts.handleResponseError(error)
							})
					}
				}
            }))
        })
    </script>
@endsection
