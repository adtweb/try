@props([
	'title' => null,
])

<div class="submenu-user__item tw-relative">
	@if ($title)
		<div class="tw-absolute tw-top-0 tw-bg-white tw-px-2 tw-py-1 tw-text-[14px] tw-text-[#aaa]"
			style="transform: translateY(-55%);">
			{{ $title }}
		</div>
	@endif

    {{ $slot }}
</div>
