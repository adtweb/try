@extends('layouts.conference-lk')

@section('title', __('my/events/personal.sections.title'))

@section('content')
	<script>
		let sections = @json($sections);
		let conference = @json($conference);
	</script>
	<div id="sections" 
		@refresh-moderators.window="refreshModerators"
		x-data="{
			conference: conference,
			form: $form('post', route('sections.mass-update', conference.slug), {
				sections: sections,
			}),
			ai: 1,
			
			save() {
				this.form.submit()
					.then(response => {
						this.form.sections = response.data
					})
					.catch(error => {
						this.$store.toasts.handleResponseError(error)
					})
			},
			add() {
				this.form.sections.push({
					slug: 'acronim' + this.ai,
					title_ru: 'Название на русском',
					title_en: 'Titile in english',
					theses_exists: false,
					moderators: [],
				})
				this.ai++
			},
			remove(id) {
				let section = this.form.sections[id]
				this.form.sections.splice(id, 1)

				if (section.id === undefined) {
					return
				}

				this.save()
			},
			addModerator(sectionId) {
				this.$dispatch('popup', 'invite')
				this.$dispatch('add-moderator', {sectionId})
			},
			refreshModerators() {
				let section = this.form.sections.find(el => el.id === this.$event.detail.sectionId)
				section.moderators = this.$event.detail.moderators
			},
			removeModerator(sectionId, userId) {
				axios
					.delete(route('moderators.destroy', this.conference.slug), {data: 
						{
							section_id: sectionId,
							user_id: userId,
						}
					})
					.then(response => {
						this.$dispatch('refresh-moderators', {
							sectionId: sectionId,
							moderators: response.data
						})
					}).catch(error => this.$store.toasts.handleResponseError(error))
			},
		}"
	>
		<x-my.breadcrumbs class="tw-mt-3" :items="[
			route('events.organization-index') => __('my/events/personal.sections.breadcrumbs.1'),
			route('conference.show', $conference->slug) => $conference->{'title_'.loc()},
			'#' => __('my/events/personal.sections.breadcrumbs.2')
		]" />

		<h1 class="edit-content__title">{{ __('my/events/personal.sections.h1') }}</h1>
	
		<template x-if="form.sections.length > 0">
			<div class="accordion">
				<template x-for="(section, id) in form.sections" :key="id">
					<div class="accordion-item">
						<input :id="'accordion-trigger-' + (section.id ?? section.slug)" class="accordion-trigger-input" type="checkbox">
						<label class="accordion-trigger" 
							:for="'accordion-trigger-' + (section.id ?? section.slug)" 
							x-text="`${section.title_{{ loc() }}} (${section.slug})`"
						></label>
						<div class="accordion-animation-wrapper">
							<div class="accordion-animation">
								<div class="accordion-transform-wrapper">
									<div class="accordion-content">
										<div class="form">
											<div class="form__line" :class="form.invalid(`sections.${id}.slug`) && '_error'">
												<input class="input" autocomplete="off" type="text" name="slug"
													placeholder="{{ __('my/events/personal.sections.slug_placeholder') }}" x-model="form.sections[id].slug"
													@input="form.validate(`sections.${id}.slug`)">
												<template x-if="form.invalid(`sections.${id}.slug`)">
													<div class="form__error" x-text="form.errors[`sections.${id}.slug`]"></div>
												</template>

												<div class="tw-m-1">
													{{ __('my/events/personal.sections.slug_tip') }}:
													<span x-text="`${conference.slug}-${form.sections[id].slug}001`"></span>
												</div>
											</div>
											<div class="form__line" :class="form.invalid(`sections.${id}.title_ru`) && '_error'">
												<input class="input" autocomplete="off" type="text" name="title_ru"
													placeholder="{{ __('my/events/personal.sections.title_ru_placeholder') }}" x-model="form.sections[id].title_ru"
													@input="form.validate(`sections.${id}.title_ru`)">
												<template x-if="form.invalid(`sections.${id}.title_ru`)">
													<div class="form__error" x-text="form.errors[`sections.${id}.title_ru`]"></div>
												</template>
											</div>
											<div class="form__line" :class="form.invalid(`sections.${id}.title_en`) && '_error'">
												<input class="input" autocomplete="off" type="text" name="title_en"
													placeholder="{{ __('my/events/personal.sections.title_en_placeholder') }}" x-model="form.sections[id].title_en"
													@input="form.validate(`sections.${id}.title_en`)">
												<template x-if="form.invalid(`sections.${id}.title_en`)">
													<div class="form__error" x-text="form.errors[`sections.${id}.title_en`]"></div>
												</template>
											</div>
										</div>
			
										<div class="moderators">
											<div class="moderators__title">
												{{ __('my/events/personal.sections.moderators') }}
											</div>
											<template x-if="section.moderators.length === 0">
												<div>{{ __('my/events/personal.sections.moderators_empty') }}</div>
											</template>
											<ol>
												<template x-for="(moderator, moderatorId) in section.moderators">
													<li>
														<div class="moderators__item">
															<div>
																<span x-text="moderator.email"></span>
																<span x-text="moderator.participant.name_{{ loc() }} ?? 'No name'"></span>
																<span x-text="moderator.participant.surname_{{ loc() }} ?? 'No surname'"></span>
																<span x-text="` - ${moderator.pivot.comment}`"></span>
															</div>
															<button 
																class="_icon-close" 
																type="button" 
																@click="removeModerator(section.id, moderator.id)"
															></button>
														</div>
													</li>
												</template>
											</ol>
										</div>
										<div class="section-actions">
											<div class="section-actions__item">
												<template x-if="section.id">
													<button 
														class="section-actions__btn button button_icon" 
														type="button" 
														@click="addModerator(section.id)"
													>
														<img src="{{ Vite::asset('resources/img/iconsfonts/invite.svg') }}" alt="Image">
														<span>{{ __('my/events/personal.sections.add_moderator') }}</span>
													</button>
												</template>
												<template x-if="!section.id">
													<div class="tw-text-secondary">{{ __('my/events/personal.sections.save_to_add') }}</div>
												</template>
											</div>
											<div class="section-actions__item">
												<button 
													class="section-actions__btn button button_outline" 
													@click="save"
													:disabled="form.processing"
												>
													{{ __('my/events/personal.sections.moderators_save') }}
												</button>
												<template x-if="!section.theses_exists && form.sections.length > 1">
													<button 
														class="section-actions__btn button button_primary"
														@click="remove(id)"
														:disabled="form.processing"
													>
														{{ __('my/events/personal.sections.moderators_remove') }}
													</button>
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
		</template>
		<template x-if="form.sections.length == 0">
			<div class="">{{ __('my/events/personal.sections.no_sections') }}</div>
		</template>
	
		<button class="button button_outline tw-mt-3" type="button" @click="add">{{ __('my/events/personal.sections.add') }}</button>
		<button class="button button_outline tw-mt-3" type="button" @click="save">{{ __('my/events/personal.sections.moderators_save') }}</button>
	</div>
@endsection

@section('popup')
	<x-popup id="invite" title="{{ __('my/events/personal.sections.popup.title') }}">
		<form class="form" id="invite-moderator"
			x-data="{
				form: $form('post', route('moderators.store', conference.slug), {
					section_id: null,
					email: '',
					comment: '',
				}),

				add() {
					this.form.section_id = this.$event.detail.sectionId
				},
				submit() {
					this.form.submit()
						.then(response => {
							this.$dispatch('refresh-moderators', {
								sectionId: this.form.section_id,
								moderators: response.data
							})
							this.show = false
							this.form.reset()
						})
						.catch(error => {
							this.$store.toasts.handleResponseError(error)
						})
				},
			}"
			@add-moderator.window="add"
			@submit.prevent="submit"
		>
			<div class="form__line">
				<input class="input" autocomplete="off" type="email" placeholder="{{ __('my/events/personal.sections.popup.email') }}" x-model="form.email">
			</div>
			<div class="form__line">
				<input class="input" autocomplete="off" type="text" placeholder="{{ __('my/events/personal.sections.popup.comment') }}" x-model="form.comment">
			</div>
			<div class="form__line">
				<button class="popup__btn button" type="submit">{{ __('my/events/personal.sections.popup.btn') }}</button>
			</div>
		</form>
	</x-popup>
@endsection
