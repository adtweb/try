<x-mail.layout.message>

<div>
{{ __('emails/emails.schedule_published.intro', ['conference_title' => $conference->{'title_'.loc()}]) }}
</div>
<div>
<ul>
@foreach ($participation->theses->load('scheduleItem.schedule') as $thesis)
@if ($thesis->report_form->value === 'stand')
<li>
{{ __('emails/emails.schedule_published.poster', [
'thesis_title' => $thesis->title,
'thesis_id' => $thesis->thesis_id,
'date' => $thesis->scheduleItem->schedule->date->format('d.m.Y'),
'time' => $thesis->scheduleItem->time_start->format('H:i'),
]) }}
</li>
@else
<li>
{{ __('emails/emails.schedule_published.oral', [
'thesis_title' => $thesis->title,
'thesis_id' => $thesis->thesis_id,
'date' => $thesis->scheduleItem->schedule->date->format('d.m.Y'),
'time' => $thesis->scheduleItem->time_start->format('H:i'),
'duration' => $thesis->scheduleItem->time_end->diffInMinutes($thesis->scheduleItem->time_start, true),
]) }}
</li>
@endif

@endforeach
</ul>

<x-mail.layout.button url="{{ route('conference.schedule', $conference->slug) }}">
{{ __('emails/emails.schedule_published.btn') }}
</x-mail.layout.button>

<span style="font-size: 10px">Powered by <a href="{{ route('home') }}" target="_blank">UCP</a></span>
</div>

<x-slot:subcopy>
@lang("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n" . 'into your web browser:', [
'actionText' => '{{ __('emails/emails.schedule_published.btn') }}',
]) <span class="break-all">[{{ route('conference.schedule', $conference->slug) }}]({{ route('conference.schedule', $conference->slug) }})</span>
</x-slot:subcopy>
	
</x-mail.layout.message>
