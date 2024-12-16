@extends('layouts.app')

@section('title', $title ?? 'Расписание мероприятия ' . $conference->{'title_' . loc()})

@section('content')

    <main class="page page_edit _single-thesis">
        <section class="edit">
            <div class="edit__container">
                <div class="edit__wrapper tw-overflow-x-auto">
                    <aside class="edit__aside aside">
                        <a href="{{ url()->previous() }}" class="aside__back _icon-arrow-back"></a>
                    </aside>
                    <div class="edit-content">
                        <nav class="edit-content__breadcrumbs breadcrumbs">
                            <ul class="breadcrumbs__list">
                                <li class="breadcrumbs__item">
                                    <a href="{{ route('conference.show', $conference->slug) }}" class="breadcrumbs__link">
                                        <span>{{ $conference->{'title_'.loc()} }}</span>
                                    </a>
                                </li>
                                <li class="breadcrumbs__item">
                                    <span class="breadcrumbs__current">{{ __('pages.schedule.breadcrumb') }}</span>
                                </li>
                            </ul>
                        </nav>
                        <div class="">
							@if (auth()->user()?->can('viewSchedules', $conference) || $conference->schedule_is_published)
								<a href="{{ route('pdf.schedule.download', $conference->slug) }}" class="">
									<svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="tw-w-7 tw-h-7">
										<path
											d="M9.1445 16.6737H10.492V13.1737H12.6445C13.0283 13.1737 13.3486 13.0454 13.6053 12.7887C13.8619 12.5321 13.9903 12.2112 13.9903 11.8263V9.67375C13.9903 9.28875 13.8619 8.96792 13.6053 8.71125C13.3486 8.45458 13.0278 8.32625 12.6427 8.32625H9.14275L9.1445 16.6737ZM10.492 11.8263V9.67375H12.6445V11.8263H10.492ZM15.8085 16.6737H19.1737C19.5564 16.6737 19.8767 16.5454 20.1345 16.2887C20.3912 16.0321 20.5195 15.7112 20.5195 15.3263V9.67375C20.5195 9.28875 20.3912 8.96792 20.1345 8.71125C19.8778 8.45458 19.557 8.32625 19.172 8.32625H15.8085V16.6737ZM17.1542 15.3263V9.67375H19.1737V15.3263H17.1542ZM22.6737 16.6737H24.0195V13.1737H26.4415V11.8263H24.0195V9.67375H26.4415V8.32625H22.6737V16.6737ZM8.20125 24.75C7.39625 24.75 6.72425 24.4805 6.18525 23.9415C5.64508 23.4013 5.375 22.7288 5.375 21.9237V3.07625C5.375 2.27125 5.64508 1.59925 6.18525 1.06025C6.72425 0.520083 7.39625 0.25 8.20125 0.25H27.0487C27.8538 0.25 28.5258 0.520083 29.0648 1.06025C29.6049 1.59925 29.875 2.27125 29.875 3.07625V21.9237C29.875 22.7288 29.6055 23.4013 29.0665 23.9415C28.5263 24.4805 27.8538 24.75 27.0487 24.75H8.20125ZM8.20125 23H27.0487C27.3171 23 27.5638 22.888 27.789 22.664C28.013 22.4388 28.125 22.1921 28.125 21.9237V3.07625C28.125 2.80792 28.013 2.56117 27.789 2.336C27.5638 2.112 27.3171 2 27.0487 2H8.20125C7.93292 2 7.68617 2.112 7.461 2.336C7.237 2.56117 7.125 2.80792 7.125 3.07625V21.9237C7.125 22.1921 7.237 22.4388 7.461 22.664C7.68617 22.888 7.93292 23 8.20125 23ZM2.95125 30C2.14625 30 1.47425 29.7305 0.93525 29.1915C0.395083 28.6513 0.125 27.9788 0.125 27.1737V6.57625H1.875V27.1737C1.875 27.4421 1.987 27.6888 2.211 27.914C2.43617 28.138 2.68292 28.25 2.95125 28.25H23.5487V30H2.95125Z"
											fill="#E25553" />
									</svg>
								</a>
								<div class="tw-min-w-[700px]" x-data="conferenceSchedule">
									<template x-if="groupBy === 'sections'">
										<template x-for="section in sections">
											<div class="">
												<div class="tw-mt-[32px] tw-text-center"
													style="font-size: 2rem; font-weight: 600; line-height: 2.5rem;" 
													x-text="section.slug + ' ' + section['title_'+conference.abstracts_lang]"
												></div>

												<template x-for="schedule in schedules">
													<div x-show="hasItems(schedule, section)">
														<div class="tw-mt-[32px] tw-mb-[16px] tw-text-center tw-text-blue" 
															style="font-size: 1.25rem; font-weight: 600; line-height: 1.875rem;"
															x-text="DateTime.fromISO(schedule.date).toLocaleString(DateTime.DATE_SHORT)"
														></div>
														<div class="">
															<label class="tw-cursor-pointer">
																<input type="checkbox" x-model="showInConferenceTimezone">
																<span>{{ __('pages.schedule.change_timezone') . " ({$conference->timezone->toString()})" }}</span>
															</label>
														</div>
														<table class="tw-w-full">
															<tbody>
																<template x-for="item in items(schedule, section)">
																	<tr class="tw-border-0 *:tw-align-top *:tw-table-cell tw-table-row *:tw-bg-[transparent]">
																		<td 
																			class="tw-font-bold tw-text-[20px]"
																			x-show="item.is_standart && item.time_start === item.time_end"
																			colspan="3" 
																			x-text="item.title"
																		></td>

																		<td 
																			class="tw-w-[12%]"
																			:style="item.type === 'break' ? 'background-color: #e5e5e5;' : ''"
																			x-show="item.is_standart && item.time_start !== item.time_end"
																			
																		>
																			<template x-if="showInConferenceTimezone">
																				<span x-text="toZoneTime(item.time_start, timezone) + ' - ' + toZoneTime(item.time_end, timezone)"></span>
																			</template>
																			<template x-if="!showInConferenceTimezone">
																				<span x-text="toLocaleTime(item.time_start) + ' - ' + toLocaleTime(item.time_end)"></span>
																			</template>
																		</td>
																		<td 
																			x-show="item.is_standart && item.time_start !== item.time_end"
																			colspan="2" 
																			x-text="item.title"
																			:style="item.type === 'break' ? 'background-color: #e5e5e5;' : ''"
																		></td>

																		<td
																			class="tw-w-[10%]"
																			x-show="!item.is_standart && item.thesis?.report_form === 'stand'"
																			x-text="item.thesis?.thesis_id"
																		></td>
																		<td
																			class="tw-w-[35%]"
																			x-show="!item.is_standart && item.thesis?.report_form === 'stand'"
																			x-html="authors(item.thesis)"
																		>
																		</td>
																		<td
																			x-show="!item.is_standart && item.thesis?.report_form === 'stand'"
																		>
																			<div>
																				<a :href="route('conference.thesis.show', [conference.slug, item.thesis?.thesis_id ?? 0])" x-html="item.thesis?.title"></a>
																			</div>
																			<div class="tw-flex tw-items-center tw-justify-start tw-gap-2 tw-my-1">
																				<template x-for="asset in item.thesis?.assets">
																					<div class="tw-mb-1 tw-capitalize tw-flex tw-items-center tw-gap-2">
																						<img class="tw-w-6 tw-h-6" src="{{ Vite::asset('resources/img/icons/pdf-icon.svg') }}" alt="Pdf icon">
																						<a :href="s3Path + asset.path" target="_blank" download x-text="asset.title"></a>
																					</div>
																				</template>
																			</div>
																			<template x-if="item.thesis?.solicited_talk">
																				<div class="tw-text-[#e25553] tw-my-1">
																					Solicited talk
																				</div>
																			</template>
																			<div class="tw-flex tw-items-center tw-justify-start tw-gap-2 tw-my-1">
																				<template x-for="tag in item.schedule_item_tags">
																					<div class="tw-rounded tw-p-1 tw-bg-[#1E4759] tw-text-[#fff] tw-px-5"
																						x-text="tag.title_en"	
																					>
																					</div>
																				</template>
																			</div>
																		</td>

																		<td
																			class="tw-w-[10%]"
																			x-show="!item.is_standart && item.thesis?.report_form !== 'stand'"
																		>
																			<template x-if="showInConferenceTimezone">
																				<span x-text="toZoneTime(item.time_start, timezone) + ' - ' + toZoneTime(item.time_end, timezone)"></span>
																			</template>
																			<template x-if="!showInConferenceTimezone">
																				<span x-text="toLocaleTime(item.time_start) + ' - ' + toLocaleTime(item.time_end)"></span>
																			</template>
																		</td>
																		<td
																			class="tw-w-[35%]"
																			x-show="!item.is_standart && item.thesis?.report_form !== 'stand'"
																			x-html="authors(item.thesis)"
																		>
																		</td>
																		<td
																			x-show="!item.is_standart && item.thesis?.report_form !== 'stand'"
																		>
																			<div>
																				<a :href="route('conference.thesis.show', [conference.slug, item.thesis?.thesis_id ?? 0])" x-html="item.thesis?.title"></a>
																			</div>
																			<div class="tw-flex tw-items-center tw-justify-start tw-gap-2 tw-my-1">
																				<template x-for="asset in item.thesis?.assets">
																					<div class="tw-mb-1 tw-capitalize tw-flex tw-items-center tw-gap-2">
																						<img class="tw-w-6 tw-h-6" src="{{ Vite::asset('resources/img/icons/pdf-icon.svg') }}" alt="Pdf icon">
																						<a :href="s3Path + asset.path" target="_blank" download x-text="asset.title"></a>
																					</div>
																				</template>
																			</div>
																			<template x-if="item.thesis?.solicited_talk">
																				<div class="tw-text-[#e25553] tw-my-1">
																					{{ __('pages.schedule.solicited_talk') }}
																				</div>
																			</template>
																			<div class="tw-flex tw-items-center tw-justify-start tw-gap-2 tw-my-1">
																				<template x-for="tag in item.schedule_item_tags">
																					<div class="tw-rounded tw-p-1 tw-bg-[#1E4759] tw-text-[#fff] tw-px-5"
																						x-text="tag.title_en"	
																					>
																					</div>
																				</template>
																			</div>
																		</td>
																	</tr>
																</template>
															</tbody>
														</table>
													</div>
												</template>
											</div>
										</template>
									</template>
								</div>

								<script>
									document.addEventListener('alpine:init', () => {
										Alpine.data('conferenceSchedule', () => ({
											conference: @json($conference),
											sections: @json($sections),
											schedules: @json($schedules),
											scheduleItems: @json($scheduleItems),
											groupBy: 'sections',
											timezone: '{{ $conference->timezone?->value }}',
											showInConferenceTimezone: true,

											items(schedule, section) {
												return this.scheduleItems.filter(el => el.schedule_id == schedule.id && el.section_id == section.id)
											},
											authors(thesis) {
												if (thesis === null) {
													return
												}

												let authors = []
												
												for (const key in thesis.authors) {
													if (Object.hasOwnProperty.call(thesis.authors, key)) {
														const el = thesis.authors[key];
														
														if (key == thesis.reporter['id']) {
															authors.push(`<strong>${el.surname_en} ${el.name_en}</strong>`)
															continue
														}

														authors.push(`${el.surname_en} ${el.name_en}`)
													}
												}
												
												return authors.join(', ')
											},
											hasItems(schedule, section) {
												let item =  this.scheduleItems.find(
													el => el.schedule_id == schedule.id && el.section_id == section.id
												) 

												return item !== undefined
											},
										}))
									})
								</script>
							@else
								<div class="tw-text-center tw-mt-4">{{ __('pages.schedule.not_published') }}</div>
							@endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
