@extends('layouts.app')

@section('title', __('pages.contacts.title'))

@section('content')
<main class="page">
    <form class="tw-flex tw-flex-col tw-min-w-96 tw-max-w-96 tw-gap-3 tw-p-2 contacts__container" @submit.prevent="submit" x-data="{
        isSent: false,
        form: $form('post', '{{ route('feedback') }}', {
            name: '',
            email: '',
            message: '',
            user_id: '{{ auth()->id() }}',
            route_name: '{{ Route::currentRouteName() }}',
            page: '{{ url()->current() }}'
        }),
    
        submit() {
            if (this.isSent) return
    
            this.form.submit()
                .then(response => {
                    this.isSent = true
                })
                .catch(err => {
                    this.$store.toasts.handleResponseError(err)
                })
        },
    }">
        <p class="tw-text-center tw-text-[18px]">{{ __('pages.contacts.h1') }}</p>
        <input class="input tw-bg-[#f8f5f2]" autocomplete="off" type="text" name="name" placeholder="{{ __('pages.contacts.name') }}*"
            x-model="form.name">
        <template x-if="form.invalid('name')">
            <div class="form__error" x-text="form.errors.name"></div>
        </template>
        <input class="input tw-bg-[#f8f5f2]" autocomplete="off" type="email" name="email" placeholder="{{ __('pages.contacts.email') }}*"
            x-model="form.email">
        <template x-if="form.invalid('email')">
            <div class="form__error" x-text="form.errors.email"></div>
        </template>
        <textarea class="input tw-bg-[#f8f5f2]" autocomplete="off" name="message" placeholder="{{ __('pages.contacts.message') }}*"
            x-model="form.message"></textarea>
        <template x-if="form.invalid('message')">
            <div class="form__error" x-text="form.errors.message"></div>
        </template>
        <button class="button button_primary" :disabled="form.processing" x-show="!isSent">{{ __('pages.contacts.btn') }}</button>
        <p class="button button_primary" x-show="isSent">{{ __('pages.contacts.success') }}</p>
    </form>
</main>
@endsection
