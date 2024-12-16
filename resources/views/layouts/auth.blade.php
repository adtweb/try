<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@include('partials.head')

    <title>@yield('title') | {{ config('app.name') }}</title>

    @yield('head_scripts')
	<script src="/js/scripts.js" defer></script>
</head>

<body>
    <div class="wrapper">
        <main class="page page_registration">
            <section class="registration page-divider">
                <div class="registration__header _auth">
                    <a href="@yield('back_link', route('home'))" class="registration__btn">
                        <img src="{{ Vite::asset('resources/img/arrow-l.svg') }}" alt="Image">
                    </a>
                    <h1 class="registration__title title">@yield('h1')</h1>
                </div>
                <div class="registration__container">
					@yield('content')
                </div>
            </section>
        </main>
    </div>
    @yield('body_scripts')
</body>

</html>
