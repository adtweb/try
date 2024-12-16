@extends('layouts.app')

@section('title', __('header.personal.messenger'))

@section('content')
	 <main class="page tw-py-2">
		 <div class="messenger__container">
			@isset($conference)
				<x-my.breadcrumbs class="tw-mt-3" :items="[
					route('events.organization-index') => __('my/events/personal.sections.breadcrumbs.1'),
					route('conference.show', $conference->slug) => $conference->{'title_'.loc()},
					'#' => 'Messenger'
					]" />
			@endisset 

			<x-messenger :conference="$conference ?? null" :chatable="$chatable ?? 'participant'" :role="$role ?? 'participant'" />
		</div>
	 </main>

	
@endsection
