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

        <main class="page page_edit _bg-white">
            <section class="edit">
                <div class="edit__container">
                    <div class="edit__wrapper">
                        @include('partials.conference-lk-menu')

                        <div class="edit-content">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </section>
        </main>

        @include('partials.footer')
    </div>
	
	@yield('popup')
	@include('partials.toasts')
    @yield('body_scripts')
</body>

</html>
