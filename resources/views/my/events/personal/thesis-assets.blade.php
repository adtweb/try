@extends('layouts.conference-lk')

@section('title', __('my/events/personal.thesis-assets.title'))

@section('content')
	@php
		$lang = $conference->abstracts_lang->value;
	@endphp

	<x-my.breadcrumbs class="tw-mt-3" :items="[
		route('events.organization-index') => __('my/events/personal.thesis-assets.breadcrumbs.1'),
		route('conference.show', $conference->slug) => $conference->{'title_'.loc()},
		'#' => __('my/events/personal.thesis-assets.breadcrumbs.2')
	]" />
	
    <h1 class="edit-content__title">{{ __('my/events/personal.thesis-assets.h1') }}</h1>
    <div class="edit-content__items tw-min-h-[40vh]" 
		x-data="assets"
	>
		<div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
			<div class="tw-flex tw-justify-start tw-gap-4">
				<div>{{ __('my/events/personal.thesis-assets.total') }}: <span x-text="filtered?.length"></span></div>
				<div>{{ __('my/events/personal.thesis-assets.unapproved') }}: <span x-text="filtered?.filter(asset => !asset.is_approved).length"></span></div>
			</div>
			<div class="tw-flex tw-justify-end tw-gap-4">
				@can('publishThesisAssets', $conference)
					<button class="button" 
						@click="publishThesisAssets"
						x-text="conference.asset_is_published ? '{{ __('my/events/personal.thesis-assets.unpublish') }}' : '{{ __('my/events/personal.thesis-assets.publish') }}'"
						:class="conference.asset_is_published ? 'button_primary' : 'button_outline'"
					></button>
				@endcan
			</div>
		</div>
		<div class="tw-flex tw-justify-between tw-gap-4 tw-mb-4">
			<div>
				<div class="tw-flex tw-items-center tw-gap-3">
					<template x-if="conference.sections.length > 0">
						<div class="tw-flex tw-items-center tw-gap-3">
							<button 
								class="button" 
								:class="activeSection === '*' ? 'button_primary' : 'button_outline'"
								@click="activeSection = '*'"
							>Все</button>
							
							<template x-for="section in conference.sections">
								<button class="button" 
									x-text="section.slug" 
									:class="activeSection === section.id ? 'button_primary' : 'button_outline'"
									@click="activeSection = section.id"
								></button>
							</template>
						</div>
					</template>

					<button 
						class="button" 
						:class="onlyNotApproved ? 'button_primary' : 'button_outline'"
						@click="onlyNotApproved = !onlyNotApproved"
					>{{ __('my/events/personal.thesis-assets.unapproved') }}</button>
				</div>
			</div>
		</div>

		<template x-if="filtered.length > 0">
			<table class="table" width="100%">
				<thead>
					<th></th>
					<th>{{ __('my/events/personal.thesis-assets.id') }}</th>
					<th>{{ __('my/events/personal.thesis-assets.title') }}</th>
					<th>{{ __('my/events/personal.thesis-assets.materials') }}</th>
					<th>{{ __('my/events/personal.thesis-assets.approved') }}</th>
					<th></th>
				</thead>
				<tbody>
					<template x-for="asset, id in filtered" :key="asset.id">
						<tr>
							<td><span x-text="id+1"></span></td>
							<td x-text="asset.thesis.thesis_id">
								1234
							</td>
							<td>
								<div>
									<a :href="route('theses.show', [conference.slug, asset.thesis.id])" x-html="asset.thesis.title"></a>
								</div>
							</td>
							<td>
								<a :href="s3Path + asset.path" target="_blank" x-text="asset.title"></a>
							</td>
							<td>
								<div class="tw-flex tw-items-center tw-justify-center" x-data="{loading: false}">
									<div class="tw-flex tw-items-center tw-w-12 tw-h-6 tw-rounded-full tw-p-1 tw-cursor-pointer tw-border tw-border-solid  tw-border-[#1e4759]"
										@click="updateApproved(asset)"
										:class="asset.is_approved ? 'tw-justify-end' : ''"
									>
										<div class="tw-flex tw-items-center tw-justify-center tw-rounded-full tw-w-[18px] tw-h-[18px] tw-select-none tw-text-[#fff]"
											:class="asset.is_approved ? 'tw-bg-[#1e4759]' : 'tw-bg-[#e25553]'"
										>
											<div class="tw-flex tw-items-center tw-justify-center tw-text-[12px]" x-text="asset.is_approved ? '✓' : '⨯'"></div>
										</div>
									</div>
								</div>
							</td>
							<td>
								<button class="tw-w-6 tw-h-6" @click="deleteAsset(asset.id)">
									<img src="{{ Vite::asset('resources/img/icons/trash.svg') }}" alt="delete">
								</button>
							</td>
						</tr>
					</template>
				</tbody>
			</table>
		</template>
		<template x-if="filtered.length === 0">
			<div class="tw-text-center tw-p-4">Ничего не найдено</div>
		</template>
        {{-- <div class="edit-content__more">
            <button class="button" type="button">Показать еще</button>
        </div> --}}
    </div>

	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('assets', () => ({
				conference: @json($conference),
				assets: @json($assets),
				filtered: @json($assets),
				activeSection: '*',
				loading: false,
				onlyNotApproved: false,

				init() {
					this.$watch('activeSection', (val) => this.filterSection())	
					this.$watch('onlyNotApproved', (val) => this.filterSection())	
				},
				filterSection() {
					let filtered

					if (this.activeSection === '*') {
						filtered = this.assets
					} else {
						filtered = this.assets.filter(el => {
							return this.activeSection == el.thesis.section_id
						})
					}

					if (this.onlyNotApproved) {
						filtered = filtered.filter(el => !el.is_approved)
					}

					this.filtered = filtered
				},
				updateApproved(asset) {
					if (this.loading) return
					
					this.loading = true
					axios
						.patch(route('assets.update-approved', [this.conference.slug, asset.id]), 
							{ is_approved: !asset.is_approved })
						.then(resp => {
							asset.is_approved = !asset.is_approved
						})
						.catch(err => this.$store.toasts.handleResponseError(err))
						.finally(() => this.loading = false)
				},
				deleteAsset(id) {
					if (this.loading) return

					if (!confirm('Вы действительно хотите удалить этот материал?')) return 
					
					this.loading = true
					axios
						.delete(route('assets.destroy', [this.conference.slug, id]))
						.then(resp => {
							this.assets = this.assets.filter(asset => asset.id !== id)
							this.filtered = this.filtered.filter(asset => asset.id !== id)
						})
						.catch(err => this.$store.toasts.handleResponseError(err))
						.finally(() => this.loading = false)
				},
				publishThesisAssets() {
					if (this.loading) return

					axios
						.post(
							route('conference.publishThesisAssets', this.conference.slug),
							{ asset_is_published: !this.conference.asset_is_published }
						)
						.then(resp => {
							this.conference.asset_is_published = !this.conference.asset_is_published
						})
						.catch(err => this.$store.toasts.handleResponseError(err))
						.finally(() => this.loading = false)
				},
			}))
		})
	</script>
@endsection
