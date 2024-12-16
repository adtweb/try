<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@yield('head_scripts')
	@include('partials.head')

	<title>@yield('title') | {{ config('app.name') }}</title>

	<script src="/js/scripts.js" defer></script>
</head>
<body>
	<div class="wrapper">
		@include('partials.header')

		@yield('content')

		@include('partials.footer')
	</div>
	@include('partials.toasts')
	@yield('body_scripts')
</body>
</html>
