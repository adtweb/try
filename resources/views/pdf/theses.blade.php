@extends('pdf.layout')

@section('styles')
	<style>
		body {
			font-size: 12px;
		}
		.acronim {
			font-size: 14px;
			margin-bottom: 15px;
		}
		.title {
			font-size: 14px;
			margin-bottom: 10px;
		}
		.authors {
			margin-bottom: 10px;
		}
		.affiliations-list {
			margin-left: 0;
			padding-left: 0;
		}
		.affiliations-list li{
			margin-left: 0;
			list-style-type: none;
		}
		.email {
			color: #124fd3;
			margin-bottom: 20px;
		}
		.text {
			margin-top: 15px;
		}

		.text p {
			margin-top: 0px;
			margin-bottom: 10px;
			text-align: justify;
		}
	</style>
@endsection

@php
	$lang = $conference->abstracts_lang->value;	
@endphp

@section('content')

	@foreach ($theses as $key => $thesis)
		
		@php
			$authors = $thesis->authors;
			$thesisId = $thesis->thesis_id;
			$title = $thesis->title;
			$reporter = $thesis->reporter;
			$contact = $thesis->contact;
			$text = $thesis->text;
			$solicitedTalk = $thesis->solicited_talk;
			
			$affiliationsList = collect();
			foreach ($authors ?? [] as $author) {
				foreach ($author['affiliations'] ?? [] as $affiliation) {
					if ($affiliationsList->contains(fn($value) => $affiliation['title_'.$lang] === $value['title_'.$lang])) {
						continue;
					}

					$affiliationsList->push($affiliation);
				}
			}
		@endphp

		@include('pdf.partials.thesis')

		@unless (count($theses) == $key + 1)
			<div class="page-break"></div>
		@endunless
	@endforeach
@endsection
