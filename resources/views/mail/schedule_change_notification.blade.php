<div>
{{ __('emails/notifications.schedule_change_notification.intro', ['conference_title' => $conference->{'title_'.loc()}]) }}:
</div>
<div>
<ul>
@foreach ($theses->load('scheduleItem.schedule') as $thesis)
@if ($thesis->report_form->value === 'stand')
<li>
{{ __('emails/notifications.schedule_change_notification.poster', [
'thesis_title' => $thesis->title,
'thesis_id' => $thesis->thesis_id,
'date' => $thesis->scheduleItem->schedule->date->format('d.m.Y'),
'time' => $thesis->scheduleItem->time_start->format('H:i'),
]) }}
</li>
@else
<li>
{{ __('emails/notifications.schedule_change_notification.oral', [
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
