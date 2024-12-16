@extends('layouts.conference-lk')

@section('title', __('my/events/personal.theses.title'))

@section('content')
	@php
		$lang = $conference->abstracts_lang->value;
	@endphp

	<x-my.breadcrumbs class="tw-mt-3" :items="[
		route('events.organization-index') => __('my/events/personal.theses.breadcrumbs.1'),
		route('conference.show', $conference->slug) => $conference->{'title_'.loc()},
		'#' => __('my/events/personal.theses.breadcrumbs.2')
	]" />
	
    <h1 class="edit-content__title">{{ __('my/events/personal.theses.h1') }}</h1>
    <div class="edit-content__items tw-min-h-[40vh]" 
		x-data="theses"
	>
		<div class="tw-flex tw-justify-start tw-gap-4 tw-mb-4">
			<div>{{ __('my/events/personal.theses.counts.total') }}: <span x-text="filtered?.length"></span></div>
			<div>{{ __('my/events/personal.theses.counts.not_revoked') }}: <span x-text="filtered?.filter(thesis => !thesis.deleted_at).length"></span></div>
			<div>{{ __('my/events/personal.theses.counts.revoked') }}: <span x-text="filtered?.filter(thesis => thesis.deleted_at).length"></span></div>
			<div>{{ __('my/events/personal.theses.counts.with_assets') }}: <span x-text="filtered?.filter(thesis => thesis.assets.length > 0).length"></span></div>
		</div>
		<div class="tw-flex tw-justify-between tw-gap-4 tw-mb-4">
			<div>
				<template x-if="conference.sections.length > 0">
					<div class="tw-flex tw-items-center tw-gap-3">
						<button 
							class="button" 
							:class="activeSection === '*' ? 'button_primary' : 'button_outline'"
							@click="activeSection = '*'"
						>{{ __('my/events/personal.theses.all') }}</button>
						
						<template x-for="section in conference.sections">
							<button class="button" 
								x-text="section.slug" 
								:class="activeSection === section.id ? 'button_primary' : 'button_outline'"
								@click="activeSection = section.id"
							></button>
						</template>

						<button 
							class="button" 
							:class="onlyWithAssets ? 'button_primary' : 'button_outline'"
							@click="onlyWithAssets = !onlyWithAssets"
						>{{ __('my/events/personal.theses.with_assets') }}</button>
					</div>
				</template>
			</div>
			<div class="tw-flex tw-items-center tw-gap-2">
				<x-loader class="tw-h-4"/>
				<button @click="getCsv">
					<svg width="30" height="30" viewBox="0 0 220 220" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M57.5401 19.6774C56.6347 19.6774 55.9008 20.3995 55.9008 21.2903V163.226C55.9008 164.117 56.6347 164.839 57.5401 164.839H198.525C199.43 164.839 200.164 164.117 200.164 163.226V21.2903C200.164 20.3995 199.43 19.6774 198.525 19.6774H57.5401ZM46.0647 21.2903C46.0647 15.0549 51.2024 10 57.5401 10H198.525C204.862 10 210 15.0549 210 21.2903V163.226C210 169.461 204.862 174.516 198.525 174.516H57.5401C51.2024 174.516 46.0647 169.461 46.0647 163.226V21.2903ZM13.1422 198.95L10 53.6513L19.8339 53.4455L22.976 198.744C22.995 199.621 23.7232 200.323 24.615 200.323H169.016V210H24.615C18.3724 210 13.2749 205.09 13.1422 198.95Z" fill="#008000"/>
						<path d="M84.9148 124.282C74.2304 124.282 67.3763 116.218 67.3763 101.959V82.2066C67.3763 68.0645 74.2304 60 84.9148 60C95.9017 60 102.453 68.0645 102.857 82.2066H94.7929C94.5913 73.2071 91.0634 68.8827 84.9148 68.8827C78.9679 68.8827 75.44 73.2071 75.44 82.2066V101.959C75.44 111.075 78.9679 115.283 84.9148 115.283C91.1642 115.283 94.6921 111.075 94.9945 101.959H102.957C102.655 116.218 95.8009 124.282 84.9148 124.282Z" fill="#008000"/>
						<path d="M127.408 124.516C116.018 124.516 109.163 115.984 109.063 104.413H116.925C117.026 111.192 120.553 115.75 127.307 115.75C133.052 115.75 136.479 112.361 136.479 106.751C136.479 93.0762 109.768 97.8682 109.768 76.9472C109.768 66.662 116.32 60 126.4 60C136.983 60 143.233 67.8308 143.434 79.2847H135.673C135.572 72.6227 132.347 68.532 126.4 68.532C120.957 68.532 117.731 71.8046 117.731 77.0641C117.731 90.0374 144.442 85.0117 144.442 106.868C144.442 117.387 137.79 124.516 127.408 124.516Z" fill="#008000"/>
						<path d="M177.044 61.5194H185.41L170.593 122.997H161.823L147.006 61.5194H155.372L162.428 93.1931C163.638 99.0369 165.25 107.686 165.956 111.66H166.46C167.166 107.686 168.778 99.0369 169.988 93.1931L177.044 61.5194Z" fill="#008000"/>
					</svg>
				</button>
				<button @click="getPdf">
					<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M9.1445 16.6737H10.492V13.1737H12.6445C13.0283 13.1737 13.3486 13.0454 13.6053 12.7887C13.8619 12.5321 13.9903 12.2112 13.9903 11.8263V9.67375C13.9903 9.28875 13.8619 8.96792 13.6053 8.71125C13.3486 8.45458 13.0278 8.32625 12.6427 8.32625H9.14275L9.1445 16.6737ZM10.492 11.8263V9.67375H12.6445V11.8263H10.492ZM15.8085 16.6737H19.1737C19.5564 16.6737 19.8767 16.5454 20.1345 16.2887C20.3912 16.0321 20.5195 15.7112 20.5195 15.3263V9.67375C20.5195 9.28875 20.3912 8.96792 20.1345 8.71125C19.8778 8.45458 19.557 8.32625 19.172 8.32625H15.8085V16.6737ZM17.1542 15.3263V9.67375H19.1737V15.3263H17.1542ZM22.6737 16.6737H24.0195V13.1737H26.4415V11.8263H24.0195V9.67375H26.4415V8.32625H22.6737V16.6737ZM8.20125 24.75C7.39625 24.75 6.72425 24.4805 6.18525 23.9415C5.64508 23.4013 5.375 22.7288 5.375 21.9237V3.07625C5.375 2.27125 5.64508 1.59925 6.18525 1.06025C6.72425 0.520083 7.39625 0.25 8.20125 0.25H27.0487C27.8538 0.25 28.5258 0.520083 29.0648 1.06025C29.6049 1.59925 29.875 2.27125 29.875 3.07625V21.9237C29.875 22.7288 29.6055 23.4013 29.0665 23.9415C28.5263 24.4805 27.8538 24.75 27.0487 24.75H8.20125ZM8.20125 23H27.0487C27.3171 23 27.5638 22.888 27.789 22.664C28.013 22.4388 28.125 22.1921 28.125 21.9237V3.07625C28.125 2.80792 28.013 2.56117 27.789 2.336C27.5638 2.112 27.3171 2 27.0487 2H8.20125C7.93292 2 7.68617 2.112 7.461 2.336C7.237 2.56117 7.125 2.80792 7.125 3.07625V21.9237C7.125 22.1921 7.237 22.4388 7.461 22.664C7.68617 22.888 7.93292 23 8.20125 23ZM2.95125 30C2.14625 30 1.47425 29.7305 0.93525 29.1915C0.395083 28.6513 0.125 27.9788 0.125 27.1737V6.57625H1.875V27.1737C1.875 27.4421 1.987 27.6888 2.211 27.914C2.43617 28.138 2.68292 28.25 2.95125 28.25H23.5487V30H2.95125Z"
							fill="#E25553" />
					</svg>
				</button>
			</div>
		</div>

		<template x-if="conference.theses.length > 0">
			<table class="table" width="100%">
				<thead>
					<th></th>
					<th>{{ __('my/events/personal.theses.id') }}</th>
					<th>{{ __('my/events/personal.theses.title') }}</th>
					<th>{{ __('my/events/personal.theses.authors') }}</th>
					<th>{{ __('my/events/personal.theses.type') }}</th>
					<th>{{ __('my/events/personal.theses.date') }}</th>
					<th></th>
				</thead>
				<tbody>
					<template x-for="thesis, id in filtered" :key="thesis.id">
						<tr>
							<td><span x-text="id+1"></span></td>
							<td x-text="thesis.thesis_id">
								1234
							</td>
							<td>
								<template x-if="!thesis.deleted_at">
									<div>
										<a :href="route('theses.show', [conference.slug, thesis.id])" x-html="thesis.title"></a>
										<div class="tw-flex tw-items-center tw-gap-3 tw-flex-wrap tw-mt-2">
											<template x-for="asset in thesis.assets">
												<div class="tw-mb-1 tw-capitalize tw-flex tw-items-center tw-gap-2">
													<img class="tw-w-6 tw-h-6" src="{{ Vite::asset('resources/img/icons/pdf-icon.svg') }}" alt="Pdf icon">
													<a :href="s3Path + asset.path" target="_blank" download x-text="asset.title"></a>
												</div>
											</template>
										</div>
									</div>
								</template>
								<template x-if="thesis.deleted_at">
									<div class="tw-text-[#e25553]">
										<span x-html="thesis.title"></span>
										<span>({{ __('my/events/personal.theses.revoked') }})</span>
									</div>
								</template>
							</td>
							<td>
								<template x-for="author in thesis.authors">
									<div x-text="author.surname_{{ $lang }} + ' ' + author.name_{{ $lang }}"></div>
								</template>
							</td>
							<td x-text="thesis.report_form"></td>
							<td>
								<time x-text="DateTime.fromISO(thesis.created_at).toLocaleString()">май 1, 2023</time>
							</td>
							<td class="table__btn">
								<template x-if="!thesis.deleted_at">
									<div class="tw-flex tw-gap-1 tw-items-center">
										@can('updateAbstracts', $conference)
											<a target="_blank" :href="route('theses.edit', [conference, thesis])">
												<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5">
													<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
												</svg>
											</a>
										@endif
										<a :href="route('pdf.thesis.download', [conference, thesis])">
											<svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path
													d="M9.1445 16.6737H10.492V13.1737H12.6445C13.0283 13.1737 13.3486 13.0454 13.6053 12.7887C13.8619 12.5321 13.9903 12.2112 13.9903 11.8263V9.67375C13.9903 9.28875 13.8619 8.96792 13.6053 8.71125C13.3486 8.45458 13.0278 8.32625 12.6427 8.32625H9.14275L9.1445 16.6737ZM10.492 11.8263V9.67375H12.6445V11.8263H10.492ZM15.8085 16.6737H19.1737C19.5564 16.6737 19.8767 16.5454 20.1345 16.2887C20.3912 16.0321 20.5195 15.7112 20.5195 15.3263V9.67375C20.5195 9.28875 20.3912 8.96792 20.1345 8.71125C19.8778 8.45458 19.557 8.32625 19.172 8.32625H15.8085V16.6737ZM17.1542 15.3263V9.67375H19.1737V15.3263H17.1542ZM22.6737 16.6737H24.0195V13.1737H26.4415V11.8263H24.0195V9.67375H26.4415V8.32625H22.6737V16.6737ZM8.20125 24.75C7.39625 24.75 6.72425 24.4805 6.18525 23.9415C5.64508 23.4013 5.375 22.7288 5.375 21.9237V3.07625C5.375 2.27125 5.64508 1.59925 6.18525 1.06025C6.72425 0.520083 7.39625 0.25 8.20125 0.25H27.0487C27.8538 0.25 28.5258 0.520083 29.0648 1.06025C29.6049 1.59925 29.875 2.27125 29.875 3.07625V21.9237C29.875 22.7288 29.6055 23.4013 29.0665 23.9415C28.5263 24.4805 27.8538 24.75 27.0487 24.75H8.20125ZM8.20125 23H27.0487C27.3171 23 27.5638 22.888 27.789 22.664C28.013 22.4388 28.125 22.1921 28.125 21.9237V3.07625C28.125 2.80792 28.013 2.56117 27.789 2.336C27.5638 2.112 27.3171 2 27.0487 2H8.20125C7.93292 2 7.68617 2.112 7.461 2.336C7.237 2.56117 7.125 2.80792 7.125 3.07625V21.9237C7.125 22.1921 7.237 22.4388 7.461 22.664C7.68617 22.888 7.93292 23 8.20125 23ZM2.95125 30C2.14625 30 1.47425 29.7305 0.93525 29.1915C0.395083 28.6513 0.125 27.9788 0.125 27.1737V6.57625H1.875V27.1737C1.875 27.4421 1.987 27.6888 2.211 27.914C2.43617 28.138 2.68292 28.25 2.95125 28.25H23.5487V30H2.95125Z"
													fill="#E25553" />
											</svg>
										</a>
									</div>
								</template>
							</td>
						</tr>
					</template>
				</tbody>
			</table>
		</template>
		<template x-if="conference.theses.length === 0">
			<div class="">{{ __('my/events/personal.theses.no_theses') }}</div>
		</template>
        {{-- <div class="edit-content__more">
            <button class="button" type="button">Показать еще</button>
        </div> --}}
    </div>

	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('theses', () => ({
				conference: @json($conference),
				filtered: @json($conference->theses),
				activeSection: '*',
				onlyWithAssets: false,
				loading: false,

				init() {
					this.$watch('activeSection', (val) => this.filterSection())	
					this.$watch('onlyWithAssets', (val) => this.filterSection())	
				},
				filterSection() {
					let filtered

					if (this.activeSection === '*') {
						filtered = this.conference.theses
					} else {
						filtered = this.conference.theses.filter(el => {
							return this.activeSection == el.section_id
						})
					}

					if (this.onlyWithAssets) {
						filtered = filtered.filter(el => el.assets.length > 0)
					}

					this.filtered = filtered
				},
				getCsv() {
					let thesesIds = this.getFilteredIds()

					this.loading = true	
					
					let slug = this.getSectionSlug()

					axios
						.get(route('csv.theses.download', this.conference.slug), {
							params: { theses: thesesIds },
							responseType: 'blob',
						})
						.then(response => saveAs(response.data, `abstracts-${slug}.csv`))
						.catch(error => this.$store.toasts.handleResponseError(error))
						.finally(() => this.loading = false)
				},
				getPdf() {
					let thesesIds = this.getFilteredIds()

					this.loading = true

					let slug = this.getSectionSlug()

					axios
						.post(
							route('pdf.theses.download', this.conference.slug), 
							{ theses: thesesIds },
							{responseType: 'blob',}
						)
						.then(response => saveAs(response.data, `abstracts-${slug}.pdf`))
						.catch(error => this.$store.toasts.handleResponseError(error))
						.finally(() => this.loading = false)
				},
				getFilteredIds() {
					let thesesIds = []
					this.filtered.forEach(el => thesesIds.push(el.id))
					
					return thesesIds
				},
				getSectionSlug() {
					let section = this.activeSection === '*' 
						? 'all' 
						: this.conference.sections
							.find(el => el.id === this.activeSection)
							.slug	
					
					return section
				},
			}))
		})
	</script>
@endsection
