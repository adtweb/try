@extends('layouts.app')

@section('title', __('pages.home.title'))

@section('content')
    <main class="page">
        <section class="search-result section-divider">
            <div class="search-result__container">
                <h2 class="search-result__title title">
                    {{ __('pages.home.h1') }}
                </h2>
                <div class="search-result__items">
                    @if ($conferences->isEmpty())
						{{ __('pages.home.empty') }}
                    @else
                        @foreach ($conferences as $conference)
                            <div class="search-result-item">
                                <div class="search-result-item__header">
                                    @foreach ($conference->subjects as $subject)
                                        <span>{{ $subject->{'title_' . loc()} }}</span>

                                        @if (!$loop->last)
                                            <span>|</span>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="search-result-item__body">
                                    <a href="{{ route('conference.show', $conference->slug) }}" class="search-result-item__title">
                                        {{ $conference->{'title_' . loc()} }}
                                    </a>
                                    <div class="search-result-item__details">
                                        <small>{{ $conference->start_date->translatedFormat('d M y') }}</small>
                                        <small>{{ $conference->organization->{'full_name_' . loc()} }}</small>
                                    </div>
                                    <div class="search-result-item__text">
                                        {{ $conference->{'description_' . loc()} }}
                                    </div>
                                    <a href="{{ route('conference.show', $conference->slug) }}"
                                        class="search-result-item__link">
										{{ __('pages.home.more') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
