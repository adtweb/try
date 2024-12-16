@extends('layouts.app')

@section('title', $conference->{'title_'.loc()})

@section('content')
    <main class="page" x-data>
        <section class="event">
            <div class="event__container">
                <div class="event-item">
                    <div class="event-item__header header-item-event">
                        <div class="header-item-event__icon">
							@if (!empty($conference->logo))
								<img src="{{ config('filesystems.disks.s3.base_url') . $conference->logo }}" alt="Logo">
							@else
                            	<img src="{{ Vite::asset('resources/img/user.jpg') }}" alt="Defaul image">
							@endif
                        </div>
                        <div class="header-item-event__info">
                            <h3 class="header-item-event__title">
                                {{ $conference->{'title_'.loc()} }}
                            </h3>
                            <div class="header-item-event__time">
                                <time>{{ $conference->start_date->translatedFormat('d M Y') }} - {{ $conference->end_date->translatedFormat('d M Y') }}</time>
								@if ($conference->organization->{'short_name_'.loc()})
                                	<span>{{ $conference->organization->{'short_name_'.loc()} }}</span>
								@else
                                	<span>{{ $conference->organization->{'full_name_'.loc()} }}</span>
								@endif
                            </div>
                            <div class="header-item-event__descr">
                                <div class="descr-item" title="{{ __('pages.conference.price_participants') }}">
                                    <div class="descr-item__img">
                                        <img src="{{ Vite::asset('resources/img/icons/student.svg') }}" alt="Image">
                                    </div>
                                    <div class="descr-item__text">
										@if (is_null($conference->price_participants))
											{{ __('pages.conference.free') }}
										@else
                                        	{{ $conference->price_participants }} ₽
										@endif
                                    </div>
                                </div>
                                <div class="descr-item" title="{{ __('pages.conference.price_visitors') }}">
                                    <div class="descr-item__img">
                                        <img src="{{ Vite::asset('resources/img/icons/projector.svg') }}" alt="Image">
                                    </div>
                                    <div class="descr-item__text">
										@if (is_null($conference->price_visitors))
											{{ __('pages.conference.free') }}
										@else
                                        	{{ $conference->price_visitors }} ₽
										@endif
                                    </div>
                                </div>
                                <div class="descr-item" title="{{ __('pages.conference.price_abstracts') }}">
                                    <div class="descr-item__img">
                                        <img src="{{ Vite::asset('resources/img/icons/star.svg') }}" alt="Image">
                                    </div>
                                    <div class="descr-item__text">
										@if (is_null($conference->abstracts_price))
											{{ __('pages.conference.free') }}
										@else
                                        	{{ $conference->abstracts_price }} ₽
										@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="event-item__body">
                        <ul>
                            <li><strong>{{ __('pages.conference.organizer') }}: </strong>
								{{ $conference->organization->{'full_name_'.loc()} }}
                            </li>
                            <li><strong>{{ __('pages.conference.description') }}: </strong>{{ $conference->{'description_'.loc()} }}
                            </li>
                            <li>
								<strong>{{ __('pages.conference.subjects') }}: </strong>
								@foreach ($conference->subjects as $subject)
									<span>
										<a href="{{ localize_route('subject', $subject->slug) }}">
											{{ $subject->{'title_'.loc()} }}
										</a>
									</span>
									@unless ($loop->last)
										<span>|</span>
									@endunless
								@endforeach
							</li>
                            <li class="d-flex">
                                <strong>{{ __('pages.conference.contacts') }}: </strong>
                                <div class="event-item__contacts">
									@unless (empty($conference->phone))
										<div class="contacts-event">
											<span>{{ __('pages.conference.phone') }}: </span><a href="tel:{{ $conference->phone->clean() }}">{{ $conference->phone->raw() }}</a>
										</div>
									@endunless
									@unless (empty($conference->email))
										<div class="contacts-event">
											<span>{{ __('pages.conference.email') }}: </span><a
												href="mailto:{{ $conference->email }}">{{ $conference->email }}</a>
										</div>
									@endunless
									@unless (empty($conference->address))
										<div class="contacts-event">
											<span>{{ __('pages.conference.address') }}: </span>
											<a target="_blank" rel="nofollow" href="https://yandex.ru/maps/?text={{ $conference->address }}">{{ $conference->address }}</a>
										</div>
									@endunless
									@if ($conference->whatsapp || $conference->telegram)
										<div class="contacts-event _flex">
											<span>{{ __('pages.conference.messangers') }}: </span>
											<div class="contacts-event__icons">
												@if ($conference->whatsapp)
													<a href="{{ $conference->whatsapp }}" target="_blank">
														<img src="{{ Vite::asset('resources/img/icons/wp.svg') }}" alt="WhatsApp-icon">
													</a>
												@endif
												@if ($conference->telegram)
													<a href="{{ $conference->telegram }}" target="_blank">
														<img src="{{ Vite::asset('resources/img/icons/tg.svg') }}" alt="Telegram-icon">
													</a>
												@endif
											</div>
										</div>
									@endif
                                </div>
                            </li>
							@unless (empty($conference->website))
								<li>
									<strong>{{ __('pages.conference.site') }}:</strong>
									<a href="{{ $conference->website }}" target="_blank" rel="nofollow">{{ $conference->website }}</a>
								</li>
							@endunless
							@auth
								@can ('viewParticipations', $conference)
									<li>
										<a href="{{ route('conference.participations', $conference->slug) }}" class="button button_primary">{{ __('pages.conference.manage') }}</a>
									</li>
								@endcan
							@endauth
                        </ul>
                    </div>
					@auth
						@if (auth()->user()->participant && $conference->end_date->addMonth()->isFuture())
							<div class="event-item__footer _border tw-relative">
								@if ($participation)
									<div class="theses">
										<div class="theses__title">
											{{ __('pages.conference.theses') }}:
										</div>
										@if ($participation->theses->isNotEmpty())
											<ol class="theses__list" x-data="theses">
												@foreach ($participation->theses as $thesis)
													<li class="">
														<div class="tw-flex tw-justify-between tw-items-center tw-gap-3">
															@if ($conference->thesis_edit_until?->endOfDay()->isPast())
																<span>{!! $thesis->title !!}</span>
															@else
																<a href="{{ route('theses.edit', [$conference->slug, $thesis->id]) }}">
																	{!! $thesis->title !!}
																</a>
															@endif

															<div class="tw-items-center tw-flex tw-gap-1">
																@can ('changeThesisAssets', $conference)
																	<button type="button" class="hover:tw-text-blue tw-transition tw-items-center tw-flex" 
																		title="{{ __('pages.conference.add_asset') }}" 
																		@click="addAsset({{ $thesis->id }})"
																	>
																		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-6 tw-h-6">
																			<path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
																		</svg>
																	</button>
																@endif
																@can ('updateAbstracts', $conference)
																	<a href="{{ route('theses.edit', [$conference->slug, $thesis->id]) }}" class="!tw-text-[#000000] hover:!tw-text-blue tw-transition">
																		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-6 tw-h-6">
																			<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
																		</svg>
																	</a>
																@endcan
																@if ($conference->end_date?->endOfDay()->isFuture())
																	<button class="hover:tw-text-[#e25553] tw-transition tw-w-6 tw-h-6" 
																		title="{{ __('pages.conference.delete_thesis') }}" 
																		@click="deleteThesis({{ $thesis->id }}, '{{ $thesis->title }}', '{{ $thesis->thesis_id }}')"
																	>
																		<img class="" src="{{ Vite::asset('resources/img/icons/trash.svg') }}" alt="Иконка мусорной корзины">
																	</button>
																@endif
															</div>
														</div>
														@if ($thesis->assets->isNotEmpty())
															<ul class="tw-mt-5" x-data="assets{{ $thesis->id }}" 
																@asset-saved.window="addNewAsset"
															>
																<template x-for="asset in assets">
																	<li class="tw-mb-1 tw-capitalize tw-flex tw-items-center tw-gap-2">
																		<img class="tw-w-6 tw-h-6" src="{{ Vite::asset('resources/img/icons/pdf-icon.svg') }}" alt="Pdf icon">
																		<a :href="s3Path + asset.path" target="_blank" download x-text="asset.title"></a>
																		@can ('changeThesisAssets', $conference)
																			<button class="hover:tw-text-[#e25553] tw-transition" 
																				title="{{ __('pages.conference.delete_asset') }}" 
																				@click="deleteAsset(asset.id)"
																			>
																				<img class="tw-w-6 tw-h-6" src="{{ Vite::asset('resources/img/icons/trash.svg') }}" alt="delete thesis">
																			</button>
																		@endif
																	</li>
																</template>
															</ul>
															<script>
																document.addEventListener('alpine:init', () => {
																	Alpine.data('assets{{ $thesis->id }}', () => ({
																		assets: @json($thesis->assets),
																		thesisId: {{ $thesis->id }},

																		addNewAsset() {
																			this.assets.push(this.$event.detail)
																		},
																		deleteAsset(id) {
																			axios	
																				.delete(route('assets.destroy', ['{{ $conference->slug }}', id]))
																				.then(resp => {
																					this.assets = this.assets.filter(asset => asset.id !== id)
																				})
																				.catch(err => this.$store.toasts.handleResponseError(err))
																		}
																	}))
																})
															</script>
														@endif
														
														@if ($thesis->scheduleItem !== null && $conference->schedule_is_published)
															<div class="tw-mt-2">
																{{ __('pages.conference.report_datetime', [
																	'date' => $thesis->scheduleItem->schedule->date->format('d.m.Y'), 
																	'time' => $thesis->scheduleItem->time_start->format('H:i')
																]) }}
															</div>

															@php
																$datetime = $thesis->scheduleItem->schedule->date->setHour($thesis->scheduleItem->time_start->hour) 
																->setMinute($thesis->scheduleItem->time_start->minute)
																->toIsoString();
															@endphp
															<div class="tw-mt-2" x-data="{date: '{{ $datetime }}'}">
																<span x-text="toLocaleDateTime(date)">
																</span>
																<span>(ваш часовой пояс)</span>													
															</div>
														@endif
													</li>
												@endforeach

												<x-popup title="{{ __('pages.conference.assets_popup.title') }}" id="add_asset">
													<form @submit.prevent="storeAsset" type="multipart/form-data">
														<div class="form__row tw-mb-2">
															<label class="form__label">{{ __('pages.conference.assets_popup.type') }} (*)</label>
															<select name="title">
																<option value="presentation">{{ __('pages.conference.assets_popup.presentation') }}</option>
																<option value="poster">{{ __('pages.conference.assets_popup.poster') }}</option>
															</select>
														</div>
														<div class="form__row">
															<input class="input tw-flex tw-pt-[2px]" type="file" name="file" accept=".pdf" required>
														</div>
														<div class="tw-mt-2">{{ __('pages.conference.assets_popup.description') }}</div>
														<div class="form__row tw-mt-2">
															<button class="button button_primary">
																{{ __('pages.conference.assets_popup.btn') }}
																<x-loader xShow="loading" class="tw-w-6 tw-text-[#fff]"></x-loader>
															</button>
														</div>
														<div class="form__error" x-text="loadingError"></div>
													</form>
												</x-popup>
											</ol>
											<script>
												document.addEventListener('alpine:init', () => {
													Alpine.data('theses', () => ({
														activeThesisId: null,
														loading: false,
														loadingError: '',

														deleteThesis(id, title, thesisId) {
															if (confirm(`${thesisId} ${title}\n{{ __('pages.conference.theses_delete_confirm') }}`)) {
																axios
																	.delete(route('theses.destroy', id))
																	.then(resp => location.reload())
																	.catch(err => this.$store.toasts.handleResponseError(err))
															}
														},
														addAsset(id) {
															this.activeThesisId = id
															this.$dispatch('popup', 'add_asset')	
														},
														storeAsset() {
															this.loading = true
															let data = new FormData(this.$event.target)
															this.loadingError = ''

															axios
																.post(route('thesis.assets.store', ['{{ $conference->slug }}', this.activeThesisId]), data)
																.then(response => {
																	this.show = false
																	this.$dispatch('asset-saved', response.data)
																})
																.catch(err => {
																	if (err.response.status == 422) {
																		this.loadingError = err.response.data.message
																		return;
																	}
																	this.$store.toasts.handleResponseError(err)
																})
																.finally(() => {
																	this.loading = false
																})
														},
													}))
												})
											</script>
										@else
											<p>{{ __('pages.conference.theses_empty') }}</p>
										@endif
									</div>

									@if ($participation->theses->isNotEmpty())
										@if ($conference->thesis_edit_until?->endOfDay()->isPast())
											<p class="tw-mb-4">{{ __('pages.conference.edit_closed') }}</p>
										@else
											<p class="tw-mb-4">
												{{ __('pages.conference.thesis_edit_until', ['date' => $conference->thesis_edit_until?->translatedFormat('j F Y')]) }}
											</p>
										@endif
									@endif

									@if ($conference->thesis_accept_until?->endOfDay()->isPast())
										<p class="tw-mb-4">{{ __('pages.conference.thesis_acceptance_closed') }}</p>
									@else
										<p class="tw-mb-4">
											{{ __('pages.conference.thesis_accept_until', ['date' => $conference->thesis_accept_until?->translatedFormat('j F Y')]) }}
										</p>
										<a href="{{ localize_route('theses.create', $conference->slug) }}" class="button">{{ __('pages.conference.send_thesis') }}</a>
									@endif

									@if ($conference->thesis_edit_until?->endOfDay()->isFuture())
										<a href="{{ localize_route('participation.edit', $conference->slug) }}" class="button">{{ __('pages.conference.edit_participation') }}</a>
									@endif
								@else
									@if ($conference->end_date->endOfDay()->isFuture())
										<a href="{{ route('participation.create', $conference->slug) }}" class="button button_primary">
											{{ __('pages.conference.create_participation') }}
										</a>
									@endif
								@endif

								@if (
									! ($conference->user_id === auth()->id() 
									|| $conference->sections->pluck('moderators')->flatten()->pluck('id')->contains(auth()->id()))
								)
									<div class="tw-absolute tw-right-1 tw-top-1"
										id="send_message_to_organizer"
										x-data="{show: false}"
										@click.outside="show = false"
									>
										<button class="button button_outline tw-p-1" @click="show = !show">
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
										</button>
										
										<ul class="tw-absolute tw-bg-[#fff] tw-p-2 tw-top-full tw-right-0 tw-w-[250px] tw-flex tw-flex-col tw-gap-2 tw-ml-0 tw-mt-1 tw-max-h-[300px] tw-overflow-y-auto" 
											style="border: 1px solid #ccc"
											x-cloak
											x-show="show"
											x-transition
										>
											@if ($conference->sections->isNotEmpty())
												<li class="tw-text-font-blue">Написать модератору секции</li>
											@endif
											@foreach ($conference->sections as $section)
												<li class="tw-text-[#999]">{{ $section->{'title_'.loc()} }}</li>

												@foreach ($section->moderators as $moderator)
													<li class="">
														<a href="{{ route('chats.view', [
															'method' => 'startChatWithModerator',
															'conferenceId' => $conference->id,
															'userId' => $moderator->id
														]) }}" 
															class="hover:tw-text-danger tw-transition"
														>
															{{ $moderator->participant->{'name_'.loc()} ?? 'No name'}} {{ $moderator->participant->{'surname_'.loc()} ?? 'No surname' }}
														</a>
													</li>
												@endforeach
											@endforeach

											@if ($conference->sections->isNotEmpty())
												<hr style="border-top: 1px solid #ccc">
											@endif
											
											<li class="">
												<a href="{{ route('chats.view', [
													'method' => 'startChatWithOrganization',
													'conferenceId' => $conference->id,
												]) }}"
													class="hover:tw-text-danger tw-transition"
												>
													Написать организатору конференции
												</a>
											</li>
										</ul>
									</div>
								@endif
							</div>
						@endif
					@endauth

					@if ($conference->schedule_is_published)
						<div class="tw-mt-4">
							<a href="{{ route('conference.schedule', $conference->slug) }}" class="button button_outline">
								{{ __('pages.conference.schedule') }}
							</a>
						</div>
					@endif

					@if ($conference->end_date->endOfDay()->isPast())
						<p class="tw-mt-4">{{ __('pages.conference.finished') }}</p>
					@endif
                </div>
            </div>
        </section>
    </main>
@endsection
