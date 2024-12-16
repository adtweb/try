@extends('layouts.conference-lk')

@section('title', __('my/events/personal.participations.title'))

@section('content')
	@php
		$lang = $conference->abstracts_lang->value;
	@endphp

	<x-my.breadcrumbs class="tw-mt-3" :items="[
		route('events.organization-index') => __('my/events/personal.participations.breadcrumbs.1'),
		route('conference.show', $conference->slug) => $conference->{'title_'.loc()},
		'#' => __('my/events/personal.participations.breadcrumbs.2')
	]" />
	
    <h1 class="edit-content__title">{{ __('my/events/personal.participations.h1') }}</h1>

	

    <div class="edit-content__items tw-min-h-[40vh]" 
		x-data="participations"
	>
		<div class="tw-flex tw-justify-between tw-gap-4 tw-mb-4">
			<div></div>
			<button @click="getCsv">
				<svg width="30" height="30" viewBox="0 0 220 220" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M57.5401 19.6774C56.6347 19.6774 55.9008 20.3995 55.9008 21.2903V163.226C55.9008 164.117 56.6347 164.839 57.5401 164.839H198.525C199.43 164.839 200.164 164.117 200.164 163.226V21.2903C200.164 20.3995 199.43 19.6774 198.525 19.6774H57.5401ZM46.0647 21.2903C46.0647 15.0549 51.2024 10 57.5401 10H198.525C204.862 10 210 15.0549 210 21.2903V163.226C210 169.461 204.862 174.516 198.525 174.516H57.5401C51.2024 174.516 46.0647 169.461 46.0647 163.226V21.2903ZM13.1422 198.95L10 53.6513L19.8339 53.4455L22.976 198.744C22.995 199.621 23.7232 200.323 24.615 200.323H169.016V210H24.615C18.3724 210 13.2749 205.09 13.1422 198.95Z" fill="#008000"/>
					<path d="M84.9148 124.282C74.2304 124.282 67.3763 116.218 67.3763 101.959V82.2066C67.3763 68.0645 74.2304 60 84.9148 60C95.9017 60 102.453 68.0645 102.857 82.2066H94.7929C94.5913 73.2071 91.0634 68.8827 84.9148 68.8827C78.9679 68.8827 75.44 73.2071 75.44 82.2066V101.959C75.44 111.075 78.9679 115.283 84.9148 115.283C91.1642 115.283 94.6921 111.075 94.9945 101.959H102.957C102.655 116.218 95.8009 124.282 84.9148 124.282Z" fill="#008000"/>
					<path d="M127.408 124.516C116.018 124.516 109.163 115.984 109.063 104.413H116.925C117.026 111.192 120.553 115.75 127.307 115.75C133.052 115.75 136.479 112.361 136.479 106.751C136.479 93.0762 109.768 97.8682 109.768 76.9472C109.768 66.662 116.32 60 126.4 60C136.983 60 143.233 67.8308 143.434 79.2847H135.673C135.572 72.6227 132.347 68.532 126.4 68.532C120.957 68.532 117.731 71.8046 117.731 77.0641C117.731 90.0374 144.442 85.0117 144.442 106.868C144.442 117.387 137.79 124.516 127.408 124.516Z" fill="#008000"/>
					<path d="M177.044 61.5194H185.41L170.593 122.997H161.823L147.006 61.5194H155.372L162.428 93.1931C163.638 99.0369 165.25 107.686 165.956 111.66H166.46C167.166 107.686 168.778 99.0369 169.988 93.1931L177.044 61.5194Z" fill="#008000"/>
				</svg>
			</button>
		</div>
		
		<template x-if="participations.length > 0">
			<table class="table" width="100%">
				<thead>
					<th></th>
					<th>{{ __('my/events/personal.participations.name') }}</th>
					<th>{{ __('my/events/personal.participations.email') }}</th>
					<th>{{ __('my/events/personal.participations.phone') }}</th>
					<th>{{ __('my/events/personal.participations.orcid') }}</th>
					<th>{{ __('my/events/personal.participations.type') }}</th>
					<th>{{ __('my/events/personal.participations.date') }}</th>
					<th></th>
				</thead>
				<tbody>
					<template x-for="participation, i in participations">
						<tr>
							<td x-text="i + 1"></td>
							<td x-text="participation.surname_{{ $lang }} + ' ' + participation.name_{{ $lang }}"></td>
							<td x-text="participation.email"></td>
							<td x-text="participation.phone?.raw"></td>
							<td>
								<template x-if="participation.orcid_id">
									<a class="tw-flex tw-items-center" :href="'https://orcid.org/' + participation.orcid_id" target="_blank" rel="nofollow">
										<img alt="ORCID logo" src="https://info.orcid.org/wp-content/uploads/2019/11/orcid_16x16.png" width="16" height="16" />
										<span class="tw-text-nowrap" x-text="participation.orcid_id"></span>
									</a>
								</template>
							</td>
							<td x-text="participation.participation_type"></td>
							<td>
								<time x-text="DateTime.fromISO(participation.created_at).toLocaleString()"></time>
							</td>
							<td>
								<a 
									:href="route('conference.messenger', {
										conference: conference.slug, 
										method: 'startChatWithParticipant',
										participantId: participation.participant_id,
									})" 
									class=""
									title="Написать сообщение"
								>
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
								</a>
							</td>
						</tr>
					</template>
				</tbody>
			</table>
		</template>
		<template x-if="participations.length === 0">
			<div class="">{{ __('my/events/personal.participations.empty') }}</div>
		</template>
        {{-- <div class="edit-content__more">
            <button class="button" type="button">Показать еще</button>
        </div> --}}
    </div>

	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('participations', () => ({
				conference: @json($conference),
				participations: @json($conference->participations),

				getCsv() {
					let participationsIds = []
					this.participations.forEach(el => participationsIds.push(el.id))
					
					// let section = this.activeSection === '*' 
					// 	? 'all' 
					// 	: this.conference.sections
					// 		.find(el => el.id === this.activeSection)
					// 		.slug

					axios
						.get(route('csv.participations.download', this.conference.slug), {
							params: { participations: participationsIds },
							responseType: 'blob',
						})
						.then(response => saveAs(response.data, `participations.csv`))
						.catch(error => this.$store.toasts.handleResponseError(error))
				},
			}))
		})
	</script>
@endsection
