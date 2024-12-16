@props([
	'id',
	'title' => '',
	'size' => 'sm',
])

<div 
	id="{{ $id }}"
	aria-hidden="true" 
	class="popup {{ 'popup_' . $size }}" 
	:class="show && 'popup_show'"
	@popup.window="open"
	@popup-close.window="close"
	x-data="{
		show: false,
		title: '{{ $title }}',

		open(event) {
			if (event.detail !== this.$root.id) return

			this.show = true
		},
		close(event) {
			if (event.detail !== this.$root.id) return

			this.show = false
		},
	}"
>
	<div class="popup__wrapper">
		<div class="popup__content">
			<button data-close type="button" class="popup__close _icon-close" @click="show = false"></button>
			<div class="popup__text">
				<div class="popup__inner">
					<div class="popup__title" x-text="title"></div>
					{{ $slot }}
				</div>
			</div>
		</div>
	</div>
</div>
