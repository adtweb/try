@extends('layouts.auth')

@section('title', __('auth.login.title'))

@section('h1', __('auth.login.auth'))

@section('content')
    <form action="{{ localize_route('login') }}" method="POST" class="registration__form form">
		@csrf
        <h2 class="form__title">@lang('auth.login.message')</h2>
        <div class="form__row">
            <label class="form__label" for="i_1">@lang('auth.login.email') (*)</label>
            <input id="i_1" class="form-block__input input" autocomplete="off" type="email" name="email"
                data-error="Ошибка" placeholder="@lang('auth.login.email')" value="{{ old('email') }}">
			@error('email')
				<div>{{ $message }}</div>
			@enderror
        </div>
        <div class="form__row">
            <label class="form__label" for="i_2">@lang('auth.login.password') (*)</label>
            <input id="i_2" class="form-block__input input" autocomplete="off" type="password" name="password"
                data-error="Ошибка" placeholder="@lang('auth.login.password')">
			@error('password')
				<div>{{ $message }}</div>
			@enderror
        </div>

        <div class="form__row">
            <div class="checkbox">
				<input id="c_1" class="checkbox__input" type="checkbox" name="remember" checked>
				<label for="c_1" class="checkbox__label">
					<span class="checkbox__text">@lang('auth.login.remember')</span>
				</label>
			</div>
        </div>

        <div class="form__row">
            <button class="form__button button button_primary" type="submit">@lang('auth.login.send')</button>
        </div>

		<div class="form__row">
			<a href="{{ route('register') }}">@lang('auth.login.register')</a>
		</div>
		<div class="form__row">
			<a href="{{ route('password.request') }}">@lang('auth.login.forgot')</a>
		</div>
    </form>
	@if (session('status'))
		<div class="mb-4 font-medium text-sm text-green-600">
			{{ session('status') }}
		</div>
	@endif
@endsection
