@props([
	'name',
	'label' => null,
	'option_in_form' => null,
	'multiple' => false,
])

@if (!$multiple)
	<div {{ $attributes->merge(['class' => 'form__select']) }} 
		@select-add-option.window="addOption"
		@if($optionInForm) :class="form.invalid('{{ $optionInForm }}') && '_error'" @endif
		x-data="{
			name: '{{ $name }}',
			selectElement: null,
			initiated: false,
			options: [],
			selectedValue: {},
			opened: false,

			init() {
				this.selectElement = this.$root.querySelector('select')
				let options =this.$root.querySelectorAll('option')
					.forEach(option => {
						this.options.push({
							value: option.getAttribute('value'),
							text: option.innerText,
							selected: option.hasAttribute('selected')
						})

						if (!this.selectedValue.value && option.hasAttribute('selected')) {
							this.selectedValue = this.options[this.options.length - 1]
						}
					})
				
				if (!this.selectedValue.value) {
					this.selectedValue = this.options[0]
				}

				@if ($optionInForm)
					this.form.{{ $optionInForm }} = this.selectedValue.value
				@endif

				this.initiated = true
			},
			changeValue(option) {
				this.selectedValue = option

				this.selectElement.value = option.value

				@if ($optionInForm)
					this.form.{{ $optionInForm }} = option.value
				@endif
			},
			addOption(event) {
				if (event.detail.name !== this.name) {
					return
				}
				
				this.options.push({
					value: event.detail.option.value,
					text: event.detail.option.text
				})
				console.log(event.detail.option)	
			},
		}"
	>
		@if ($label !== null)
			<label class="form__label">{{ $label }}</label>
		@endif
		
		<select name="{{ $name }}" class="input select_custom" x-show="!initiated">
			{{ $slot }}
		</select>

		<div class="select__ui" x-show="initiated" :class="{ 'select__ui_opened': opened }">
			<button class="ui__header" type="button" @click="opened = !opened" @click.outside="opened = false">
				<div class="ui__selection">
					<span class="selection__content" x-text="selectedValue.text"></span>
				</div>
			</button>

			<div class="ui__body">
				<div class="ui__items">
					<div class="items-wrap">
						<template x-for="option in options" :key="option.value">
							<div class="ui__item" 
								x-show="option.value !== selectedValue.value"
								x-text="option.text" 
								:class="option.selected && 'ui__item_selected'"
								@click="changeValue(option)"
							></div>
						</template>
					</div>
				</div>
			</div>
		</div>

		@if ($optionInForm)
			<template x-if="form.invalid('{{ $optionInForm }}')">
				<div class="form__error" x-text="form.errors.{{ $optionInForm }}"></div>
			</template>
		@endif
	</div>
@else
	<div {{ $attributes->merge(['class' => 'form__select']) }} 
		@if($optionInForm) :class="form.invalid('{{ $optionInForm }}') && '_error'" @endif
		x-data="{
			selectElement: null,
			initiated: false,
			options: [],
			selectedValues: [],
			opened: false,

			init() {
				this.selectElement = this.$root.querySelector('select')
				let options =this.$root.querySelectorAll('option')
					.forEach(option => {
						this.options.push({
							value: option.getAttribute('value'),
							text: option.innerText,
							selected: option.hasAttribute('selected')
						})

						if (option.hasAttribute('selected')) {
							this.selectedValues.push(this.options[this.options.length - 1])
						}
					})
				
				@if ($optionInForm)
					this.form.{{ $optionInForm }} = this.selectedValues.map(option => option.value)
				@endif

				this.initiated = true
			},
			changeValue(option) {
				if (this.selectedValues.some(el => el.value === option.value)) {
					this.selectedValues = this.selectedValues.filter(el => el.value !== option.value)
				} else {
					this.selectedValues.push(option)
				}

				this.selectElement.value = this.selectedValues.map(el => el.value).join(',')

				@if ($optionInForm)
					this.form.{{ $optionInForm }} = this.selectedValues.map(option => option.value)
				@endif
			}
		}"
	>
		@if ($label !== null)
			<label class="form__label">{{ $label }}</label>
		@endif
		
		<select name="{{ $name }}" class="input select_custom" x-show="!initiated" multiple>
			{{ $slot }}
		</select>

		<div class="select__ui" x-show="initiated" :class="{ 'select__ui_opened': opened }" @click.outside="opened = false">
			<button class="ui__header" type="button" @click="opened = !opened">
				<div class="ui__selection">
					<span class="selection__content" x-text="selectedValues.map(option => option.text).join(', ')"></span>
				</div>
			</button>

			<div class="ui__body">
				<div class="ui__items">
					<div class="items-wrap">
						<template x-for="option in options" :key="option.value">
							<div class="ui__item" 
								x-text="option.text" 
								:class="selectedValues.some(el => el.value === option.value) && 'ui__item_selected'"
								@click="changeValue(option)"
							></div>
						</template>
					</div>
				</div>
			</div>
		</div>

		@if ($optionInForm)
			<template x-if="form.invalid('{{ $optionInForm }}')">
				<div class="form__error" x-text="form.errors.{{ $optionInForm }}"></div>
			</template>
		@endif
	</div>
@endif
