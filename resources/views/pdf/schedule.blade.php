@extends('pdf.layout')

@section('styles')
	<style>
		body {
			font-family: DejaVu Sans;
		}
		table {
			border-collapse: collapse;
		}
		.bold {
			font-weight: bold;
		}

		.section-title {
			font-size: 1.3rem; 
			font-weight: 700; 
			text-align: center;
		}

		.day-title {
			font-size: 1.1rem; 
			font-weight: 400; 
			opacity: 0.7;
			text-align: center;
			margin-top: 0.5rem;
			margin-bottom: 1.5rem;
		}

		td {
			padding: 5px 5px 10px 5px;
			vertical-align: top;
			text-align: left;
			font-size: 0.75rem;
		}

		.cell_authors {
			padding-left: 3rem;
			width: 35%;
		}
		.cell_title {
			padding-left: 3rem;
		}
		.tag {
			padding: 0.25rem;
			border-radius: 0.25rem;
			margin-right: 0.5rem;
			color: #555;
			font-weight: bold;
		}
		.break {
			background-color: #e2e2e2;
		}
		.page-break {
			page-break-after: always;
		}
	</style>
@endsection

@section('content')
	@foreach ($sections as $key => $section)
			{{-- @if ($key > 1)
				@continue
			@endif --}}
		@php
			if ($section->scheduleItems->isEmpty()) {
				continue;
			}
		@endphp
		<div class="section-title">{{ $section->slug }} {{ $section->title_en }}</div>

		@foreach ($conference->schedules->sortBy('date') as $schedule)
			@php
				$items = $section->scheduleItems->where('schedule_id', $schedule->id)->load('thesis');

				if ($items->isEmpty()) {
					continue;
				}
			@endphp

			<div class="day-title">{{ $schedule->date->translatedFormat('d.m.Y') }}</div>

			<table width="100%">
				@foreach ($items as $item)
					@if ($item->is_standart)
						@if ($item->time_start->eq($item->time_end))
							<tr><td colspan="3" style="font-weight: 700">{{ $item->title }}</td></tr>
						@else
							<tr>
								<td width="15%" class="@if($item->type?->value === 'break') break @endif">{{ $item->time_start->format('H:i') }} - {{ $item->time_end->format('H:i') }}</td>
								<td colspan="2" class="cell_title @if($item->type?->value === 'break') break bold @endif">{{ $item->title }}</td>
							</tr>
						@endif
						
					@else
						@if ($item->thesis->report_form->value === 'stand')
							<tr>
								<td width="15%">{{ $item->thesis->thesis_id }}</td> 
								@php
									$authors = '';
									foreach ($item->thesis->authors as $key => $author) {
										if ($key == $item->thesis->reporter['id']) {
											$authors .= '<strong>'.$author['surname_en'] . ' ' . $author['name_en'] . '</strong>, ';
										} else {
											$authors .= $author['surname_en'] . ' ' . $author['name_en'] . ', ';
										}
									}
								@endphp
								<td class="cell_authors">
									{!! trim($authors, ', ') !!}
								</td>
								<td class="cell_title">{!! $item->thesis->title !!}</td>
							</tr>
						@else
							<tr>
								<td>{{ $item->time_start->format('H:i') }} - {{ $item->time_end->format('H:i') }}</td> 
								@php
									$authors = '';
									foreach ($item->thesis->authors as $key => $author) {
										if ($key == $item->thesis->reporter['id']) {
											$authors .= '<strong>'.$author['surname_en'] . ' ' . $author['name_en'] . '</strong>, ';
										} else {
											$authors .= $author['surname_en'] . ' ' . $author['name_en'] . ', ';
										}
									}
								@endphp
								<td class="cell_authors">
									{!! trim($authors, ', ') !!}
								</td>
								<td class="cell_title">
									<div>
										{!! $item->thesis->title !!}
									</div>
									@if ($item->thesis->solicited_talk)
										<div style="color: #e25553;">
											Solicited talk
										</div>
									@endif
									<div style="margin-top: 5px">
										@foreach ($item->scheduleItemTags as $tag)
											<span class="tag">{{ $tag->title_en }}</span>
										@endforeach
									</div>
								</td>
							</tr>	
						@endif
					@endif
				@endforeach
			</table>
		@endforeach
		@if (!$loop->last)
			<div class="page-break"></div>
		@endif
	@endforeach
@endsection
