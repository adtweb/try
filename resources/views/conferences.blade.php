@extends('layouts.app')

@section('title', $title ?? 'Конференции')

@section('content')
<main class="page">
	@if (!empty($breadcrumbs))
		<div class="section-divider white-block">
			<div class="white-block__container">
				<div class="white-block__inner">
					<nav class="white-block__breadcrumbs breadcrumbs">
						<ul class="breadcrumbs__list">
							<li class="breadcrumbs__item">
								<a href="{{ route('home') }}" class="breadcrumbs__link">{{ __('pages.home.title') }}</a>
							</li>
							<li class="breadcrumbs__item">
								<span class="breadcrumbs__current">{{ $breadcrumbs }}</span>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	@endif
	<section class="subject-result page-divider">
		<div class="subject-result__container">
			<div class="subject-result__items">
				<div class="subject-result__item">
					<h2 class="subject-result__title title-bg">
						{{ $h1 ?? 'Все конференции' }} (<span>{{ $conferences->count() }}</span>)
					</h2>
					<ul class="subject-result__list list-result">
						@if ($conferences->isEmpty())
							<li class="list-result__item">
								{{ __('pages.conferences.empty') }}
							</li>
						@else
							@foreach ($conferences as $conference)
								<li class="list-result__item">
									<div class="list-result__title tw-flex tw-justify-between tw-gap-2 tw-align-start">
										<a href="{{ localize_route('conference.show', $conference->slug) }}">{{ $conference->{'title_'.loc()} }}</a>
										@if (auth()->user()?->has('conferences'))
											<a href="{{ route('conference.messenger', $conference->slug) }}" class="tw-text-base" 
												x-data="{count: {{ $conference->unreadOrganizerChatsCount() }}}"
												x-cloak
												x-show="count > 0"
											>
												Непрочитанных чатов: <span x-text="count"></span>
											</a>
										@endif
									</div>
									<div class="list-result__details">
										<time>{{ $conference->start_date->translatedFormat('d M Y') }} - {{ $conference->end_date->translatedFormat('d M Y') }}</time>
									</div>
									<div class="list-result__body body-result">
										<div class="body-result__item">
											<strong>{{ __('pages.conferences.organizer') }}:</strong>
											<span>{{ $conference->organization->{'full_name_'.loc()} }}</span>
										</div>
										<div class="body-result__text">
											<strong>{{ __('pages.conferences.description') }}:</strong> {{ $conference->{'description_'.loc()} }}
										</div>
										<div class="body-result__item">
											<strong>{{ __('pages.conferences.id') }}:</strong>
											<span>{{ $conference->id }}</span>
										</div>
										<div class="body-result__item">
											<strong>{{ __('pages.conferences.subjects') }}:</strong>
											@foreach ($conference->subjects as $subject)
												<span>
													<a href="{{ localize_route('subject', $subject->slug) }}">
														{{ $subject->{'title_'.loc()} }}
													</a>
												</span>
											@endforeach
										</div>
										@if ($conference->website)
											<div class="body-result__item">
												<strong>{{ __('pages.conferences.website') }}:</strong>
												<span><a href="{{ $conference->website }}" target="_blank">{{ $conference->website }}</a></span>
											</div>
										@endif
									</div>
								</li>
							@endforeach
						@endif
					</ul>
				</div>
			</div>
		</div>
	</section>
</main>
@endsection
