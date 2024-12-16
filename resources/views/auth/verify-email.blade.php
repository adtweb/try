@extends('layouts.app')

@section('title', __('auth.verify-email.title'))

@section('content')
    <main class="page">
        <div class="page__container">
            <div class="block-accent">
                <div class="text-accent text-accent_lg">
                    {{ __('auth.verify-email.h1') }}
                </div>
                <form method="POST" action="/email/verification-notification">
                    @csrf
                    <button class="button button_primary">{{ __('auth.verify-email.btn') }}</button>
                </form>

                @if (session('status') == 'verification-link-sent')
                    <style>
                        .session-notification {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin-top: 20px;
                            padding: 20px;
                            background-color: var(--bg-accent);
                            color: #fff;
                        }
                    </style>
                    <div class="session-notification" x-data="{ show: true }" x-show="show" x-transition>
                        {{ __('auth.verify-email.notification') }}
                        <button @click="show=false">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20px"
                                height="20px">
                                <path
                                    d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
