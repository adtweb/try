@props([
	'name', 
	'image' => null,
	'title' => __('components.forms.input-image.title'),
	'loadButtonText' => __('components.forms.input-image.load_button_text'),
	'deleteButtonText' => __('components.forms.input-image.delete_button_text'),
	'deleteUrl' => null,
	'cover' => true,
])

<div id="formImage" x-data="{
	photo: '{{ $image }}',
	
	loadPreview(e) {
		const url = URL.createObjectURL(e.target.files[0]);
		this.$refs.preview.src = url;
		this.form.{{ $name }} = e.target.files[0]
	},
	deletePreview() {
		axios
			.delete('{{ $deleteUrl }}')
			.then(resp => {
				this.photo = null
			})
			.catch(err => this.$store.toasts.handleResponseError(err))
	}
}">
	<label class="form__label" for="formImage">
		{{ $title }}
	</label>
	<div class="file">
		<div class="file__item">
			<div id="formPreview" class="file__preview">
				<template x-if="photo !== null && photo !== ''">
					<img x-ref="preview" :src="s3Path + photo" class="@if ($cover) !tw-object-cover @else !tw-object-contain @endif">
				</template>
				<template x-if="photo === null || photo === ''">
					<img x-ref="preview" src="{{ Vite::asset('resources/img/no-image.jpg') }}" class="@if ($cover) !tw-object-cover @else !tw-object-contain @endif">
				</template>
			</div>
			<input id="formImage" accept="image/*" type="file" name="image"
				class="file__input" @change.stop="loadPreview">
			<div class="file__btns">
				<div class="file__button button">{{ $loadButtonText }}</div>
				@if ($deleteUrl)
					<template x-if="photo !== null && photo !== ''">
						<button class="button button_outline" type="button" @click="deletePreview">{{ $deleteButtonText }}</button>
					</template>
				@endif
				<template x-if="form.invalid('image')">
					<div class="form__error" x-text="form.errors.image"></div>
				</template>
			</div>
		</div>
	</div>
</div>
