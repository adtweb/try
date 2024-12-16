@extends('layouts.app')

@section('title', strip_tags($thesis->title))

@section('content')
    <main class="page page_edit _single-thesis">
        <section class="edit">
            <div class="edit__container">
                <div class="edit__wrapper">
                    <aside class="edit__aside aside">
                        <a href="{{ route('theses.index-by-conference', $conference->slug) }}"
                            class="aside__back _icon-arrow-back" data-da=".content-thesis__title, 767.98"></a>
                    </aside>
                    <div class="edit-content">
                        <nav class="edit-content__breadcrumbs breadcrumbs" data-da=".edit__wrapper, 767.98, first">
                            <ul class="breadcrumbs__list">
                                <li class="breadcrumbs__item">
                                    <a href="{{ route('events.organization-index') }}" class="breadcrumbs__link">
                                        <span>{{ __('my/events/personal.thesis.breadcrumbs.1') }}</span>
                                    </a>
                                </li>
                                <li class="breadcrumbs__item">
                                    <a href="{{ route('conference.show', $conference->slug) }}" class="breadcrumbs__link">
                                        <span title="{{ $conference->{'title_'.loc()} }}">{{ $conference->{'title_'.loc()} }}</span>
                                    </a>
                                </li>
                                <li class="breadcrumbs__item">
                                    <a href="{{ route('theses.index-by-conference', $conference->slug) }}" class="breadcrumbs__link">
                                        <span>{{ __('my/events/personal.thesis.breadcrumbs.2') }}</span>
                                    </a>
                                </li>
                                <li class="breadcrumbs__item">
									<span class="breadcrumbs__current">{!! $thesis->title !!}</span>
                                </li>
                            </ul>
                        </nav>
                        <div class="thesis-single">
                            <div class="thesis-single__content content-thesis">
                                <h1 class="content-thesis__title">{!! $thesis->title !!}</h1>
								<h2 class="tw-font-bold tw-text-[1.2rem] tw-mb-[25px]">{{ $thesis->thesis_id }}</h2>

								@php
									$lang = $conference->abstracts_lang->value;	
									
									$affiliationsList = collect();
									foreach ($thesis->authors ?? [] as $author) {
										foreach ($author['affiliations'] ?? [] as $affiliation) {
											if ($affiliationsList->contains(fn($value) => $affiliation['title_'.$lang] === $value['title_'.$lang])) {
												continue;
											}

											$affiliationsList->push($affiliation);
										}
									}
								@endphp

                                <div class="content-thesis__footnotes footnotes">
                                    <div class="footnotes__authors">
										@foreach ($thesis->authors as $key => $author)
											@php
												$authorAffiliationIndexes = [];
												foreach ($author['affiliations'] ?? [] as $affiliation) {
													if ($affiliationsList->contains(fn($value) => $affiliation['title_'.$lang] === $value['title_'.$lang])) {
														$index = $affiliationsList->search(fn($val) => $val['title_'.$lang] === $affiliation['title_'.$lang]);
														$authorAffiliationIndexes[] = $index + 1;
													}
												}
											@endphp
											<span class="footnotes__item @if(!$loop->first) -tw-ml-2 @endif @if($thesis->reporter['id'] == $key) _main @endif">
												{{ $author['name_'.$lang] }}@if (!empty($author['middle_name_'.$lang])) {{ mb_substr($author['middle_name_'.$lang], 0, 1) }}.@endif {{ $author['surname_'.$lang] }}<sup>{{ implode(',', $authorAffiliationIndexes) }}</sup>@if (!$loop->last), @endif
											</span>
										@endforeach
                                    </div>
                                    <div class="footnotes__organizations">
										@foreach ($affiliationsList as $key => $affiliation)
											<span class="footnotes__item">
												<sup>{{ $key + 1 }}</sup>
												{{ $affiliation['title_'.$lang] }}@if($affiliation['no_affiliation']), {{ $affiliation['country']["name_$lang"] }}@endif
											</span>
										@endforeach
                                    </div>
                                </div>

								@if ($thesis->solicited_talk)
									<div class="tw-text-[1.1rem] tw-mb-[20px] tw-text-[#e25553]">{{ __('my/events/personal.thesis.solicited_talk') }}</div>
								@endif

                                <div class="content-thesis__text">
                                    {!! $thesis->text !!}
                                </div>

								@if ($thesis->assets->isNotEmpty())
									<h2 class="tw-font-bold tw-text-[1.2rem] tw-mb-[25px]">{{ __('my/events/personal.thesis.assets') }}</h2>
									<ul class="tw-mt-5 tw-mb-5" x-data="assets{{ $thesis->id }}" 
										@asset-saved.window="addNewAsset"
									>
										<template x-for="asset in assets">
											<li class="tw-mb-1 tw-capitalize tw-flex tw-items-center tw-gap-2">
												<img class="tw-w-6 tw-h-6" src="{{ Vite::asset('resources/img/icons/pdf-icon.svg') }}" alt="Pdf icon">
												<a class="tw-text-[#1e4759]" :href="s3Path + asset.path" target="_blank" download x-text="asset.title"></a>
												@can ('changeThesisAssets', $conference)
													<button class="hover:tw-text-[#e25553] tw-transition" 
														title="Удалить материалы" 
														@click="deleteAsset(asset.id)"
													>
														<img class="tw-w-6 tw-h-6" src="{{ Vite::asset('resources/img/icons/trash.svg') }}" alt="Pdf icon">
													</button>
												@endif
											</li>
										</template>
									</ul>
									<script>
										document.addEventListener('alpine:init', () => {
											Alpine.data('assets{{ $thesis->id }}', () => ({
												assets: @json($thesis->assets),
												thesisId: {{ $thesis->id }},

												addNewAsset() {
													this.assets.push(this.$event.detail)
												},
												deleteAsset(id) {
													axios	
														.delete(route('assets.destroy', ['{{ $conference->slug }}', id]))
														.then(resp => {
															this.assets = this.assets.filter(asset => asset.id !== id)
														})
														.catch(err => this.$store.toasts.handleResponseError(err))
												}
											}))
										})
									</script>
								@endif

                                <div class="content-thesis__date">
                                    {{ __('my/events/personal.thesis.sent') }} {{ $thesis->created_at->diffForHumans() }}
									(<span x-data x-text="DateTime.fromISO('{{ $thesis->created_at->toISOString() }}').toLocaleString(DateTime.DATETIME_MED)"></span>)
                                </div>
                            </div>
                            <div class="thesis-single__buttons">
                                <a href="{{ route('pdf.thesis.download', [$conference->slug, $thesis->id]) }}" download class="button">
									{{ __('my/events/personal.thesis.pdf') }}
								</a>
								@can('updateAbstracts', $conference)
                                	<a target="_blank" href="{{ route('theses.edit', [$conference->slug, $thesis->id]) }}" class="button">
										{{ __('my/events/personal.thesis.edit') }}
									</a>
								@endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
