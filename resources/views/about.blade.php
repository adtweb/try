@extends('layouts.app')

@section('title', __('pages.about.title'))

@section('content')
<main class="page">
	<x-breadcrumbs :items="[
		route('home') => __('pages.about.breadcrumbs.1'),
		'#' => __('pages.about.breadcrumbs.2')
	]" />

	<div class="page-divider">
		<div class="about__container">
			<h1 class="subject-result__title title-bg">{{ __('pages.about.h1') }}</h1>
			<p class="tw-mb-3 tw-text-[1rem]">{{ __('pages.about.01') }}</p>
			<p class="tw-mb-3 tw-text-[1rem]">{{ __('pages.about.02') }}</p>
			<h2 class="tw-my-5  tw-text-[1.2rem]">{{ __('pages.about.03') }}</h2>
			<p class="tw-mb-3 tw-text-[1rem]">{{ __('pages.about.04') }}</p>
			<h2 class="tw-my-5  tw-text-[1.2rem]">{{ __('pages.about.05') }}</h2>
			<ul class="tw-ms-6">
				<li>
					<p class="tw-mb-3 tw-text-[1rem]">{{ __('pages.about.list.1.title') }}</p>
					<ul class="tw-ms-8 tw-mb-3">
						<li class="tw-mb-2 tw-text-[1rem] tw-list-disc">{{ __('pages.about.list.1.1') }}</li>
						<li class="tw-mb-2 tw-text-[1rem] tw-list-disc">{{ __('pages.about.list.1.2') }}</li>
						<li class="tw-mb-2 tw-text-[1rem] tw-list-disc">{{ __('pages.about.list.1.3') }}</li>
						<li class="tw-mb-2 tw-text-[1rem] tw-list-disc">{{ __('pages.about.list.1.4') }}</li>
					</ul>
				</li>
				<li>
					<p class="tw-mb-3 tw-text-[1rem]">{{ __('pages.about.list.2.title') }}</p>
					<ul class="tw-ms-8">
						<li class="tw-mb-2 tw-text-[1rem] tw-list-disc">{{ __('pages.about.list.2.1') }}</li>
						<li class="tw-mb-2 tw-text-[1rem] tw-list-disc">{{ __('pages.about.list.2.2') }}</li>
						<li class="tw-mb-2 tw-text-[1rem] tw-list-disc">{{ __('pages.about.list.2.3') }}</li>
					</ul>
				</li>
			</ul>
			<h2 class="tw-my-5  tw-text-[1.2rem]">{{ __('pages.about.06') }}</h2>
			<p class="tw-mb-3 tw-text-[1rem]">{{ __('pages.about.07') }}</p>
			<p class="tw-mb-3 tw-text-[1rem]">{{ __('pages.about.08') }}</p>
		</div>
	</div>
</main>
@endsection
