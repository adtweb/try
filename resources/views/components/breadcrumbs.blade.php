@props(['items'])

<nav {{ $attributes->merge(['class' => 'section-divider white-block']) }}>
	<div class="white-block__container">
		<div class="white-block__inner">
			<nav class="white-block__breadcrumbs breadcrumbs">
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
		</div>
	</div>
</nav>
