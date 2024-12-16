@props(['items'])

<nav {{ $attributes->merge(['class' => 'edit-content__breadcrumbs breadcrumbs']) }}>
	<ul class="breadcrumbs__list">
		@foreach ($items as $link => $text)
			@if ($loop->last)
				<li class="breadcrumbs__item">
					<span class="breadcrumbs__current">{{ $text }}</span>
				</li>
			@else
				<li class="breadcrumbs__item">
					<a href="{{ $link }}" class="breadcrumbs__link">
						<span title="{{ $text }}">{{ $text }}</span>
					</a>
				</li>
			@endif
		@endforeach
	</ul>
</nav>
