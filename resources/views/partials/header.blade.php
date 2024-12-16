<header class="header">
    <div class="header__top">
        <div class="header__container">
            <a href="/" class="logo">
                <span>ucp</span>
                <span>Universal Conference Portal</span>
            </a>
            <div class="header__action">
                <div class="lang">
                    <button class="lang__btn _icon-arrow" type="button">{{ app()->getLocale() }}</button>
                    <ul class="lang__submenu submenu">
						@foreach (LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
							@if (app()->getLocale() !== $localeCode)
								<li class="submenu__item">
									<a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="submenu__link">{{ $localeCode }}</a>
								</li>
							@endif
						@endforeach
                    </ul>
                </div>
                
				@auth
					<div class="header__btns">
						{{-- <div class="header__link header__link_notification">
							<span></span>
							<img src="{{ Vite::asset('resources/img/notification.svg') }}" alt="Image">
							<div class="submenu-user">
								<div class="submenu-user__header">
									<strong>Ваши уведомления</strong>
								</div>
								<div class="submenu-user__body">
									<ul class="messages">
										<li class="message">
											<a class="stretched-link" href=""></a>
											<div class="message__inner">
												<img src="img/user.jpg" alt="Image">
												<div class="message__body">
													<div class="message__header">
														<strong>IEEE</strong>
														<div class="circle"></div>
													</div>
													<div class="message__text">
														Приглашение на участие в конференции Приглашение на участие в конференции
													</div>
												</div>
											</div>
										</li>
										<li class="message">
											<a class="stretched-link" href=""></a>
											<div class="message__inner">
												<img src="img/user.jpg" alt="Image">
												<div class="message__body">
													<div class="message__header">
														<strong>IEEE</strong>
														<div class="circle"></div>
													</div>
													<div class="message__text">
														Приглашение на участие в конференции
													</div>
												</div>
											</div>
										</li>
										<li class="message">
											<a class="stretched-link" href=""></a>
											<div class="message__inner">
												<img src="img/user.jpg" alt="Image">
												<div class="message__body">
													<div class="message__header">
														<strong>IEEE</strong>
														<div class="circle circle_read"></div>
													</div>
													<div class="message__text">
														Приглашение на участие в конференции
													</div>
												</div>
											</div>
										</li>
										<li class="message">
											<a class="stretched-link" href=""></a>
											<div class="message__inner">
												<img src="img/user.jpg" alt="Image">
												<div class="message__body">
													<div class="message__header">
														<strong>IEEE</strong>
														<div class="circle circle_read"></div>
													</div>
													<div class="message__text">
														Приглашение на участие в конференции
													</div>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div> --}}
						<div class="header__link header__link_user">
							{{-- <div class="tw-w-[100px] tw-h-[100px] tw-rounded-full tw-overflow-hidden">
								@if (participant()?->photo)
									<img src="{{ config('filesystems.disks.s3.base_url') . participant()?->photo }}" alt="Logo">
								@else
									<img src="{{ Vite::asset('resources/img/user.jpg') }}" alt="Defaul image">
								@endif
							</div> --}}
							<span>{{ auth()->user()->email }}</span>
							<div class="submenu-user">
								<div class="submenu-user__header">
									{{-- <strong @unless(auth()->user()->email_verified_at) class="red" @endunless>{{ auth()->user()->email }}</strong> --}}
								</div>
								<div class="submenu-user__body">
									@if (auth()->user()->email_verified_at)
										{{-- Пользователь --}}
										<x-header.submenu title="{{ __('header.personal.participant') }}">
											@if (auth()->user()->participant()->exists())
												<ul class="submenu-user__list">
													<div class="tw-flex tw-justify-center">
														<div class="tw-w-[80px] tw-h-[80px] tw-rounded-full tw-overflow-hidden">
															@if (participant()?->photo)
																<img src="{{ config('filesystems.disks.s3.base_url') . participant()?->photo }}" alt="Logo">
															@else
																<img src="{{ Vite::asset('resources/img/user.jpg') }}" alt="Defaul image">
															@endif
														</div>
													</div>

													@if (Route::has('participant.edit'))
														<li>
															<a class="submenu-user__link" href="{{ route('participant.edit') }}">
																{{ __('header.personal.account') }}
															</a>
														</li>
													@endif
													@if (Route::has('events.participant-index'))
														<li>
															<a class="submenu-user__link" href="{{ route('events.participant-index') }}">
																{{ __('header.personal.events') }}
															</a>
														</li>
													@endif
													@if (Route::has('chats.view'))
														<li>
															<a class="submenu-user__link" href="{{ route('chats.view') }}">
																{{ __('header.personal.messenger') }}
																<span class="tw-px-1 tw-bg-danger !tw-text-white tw-rounded-full !tw-text-sm"
																	x-data="{count: {{ participant()->unreadChatsCount() }}}"
																	x-text="count"
																	x-cloak
																	x-show="count > 0"	
																></span>
															</a>
														</li>
													@endif
												</ul>
											@else
												<a href="{{ route('participant.create') }}" class="button button_outline">
													{{ __('header.personal.register_participant') }}
												</a>
											@endif
										</x-header.submenu>
										{{-- Организация --}}
										<x-header.submenu title="{{ __('header.personal.organizer') }}">
											<ul class="submenu-user__list">
												@if (Route::has('conference.create'))
													<li>
														<a class="submenu-user__link" href="{{ localize_route('conference.create') }}">
															{{ __('header.personal.create_event') }}
														</a>
													</li>
												@endif
												@if (Route::has('events.organization-index'))
													<li>
														<a class="submenu-user__link" href="{{ route('events.organization-index') }}">
															{{ __('header.personal.created_events') }}
															{{-- //TODO messenges count --}}
															{{-- <span class="tw-px-1 tw-bg-danger !tw-text-white tw-rounded-full !tw-text-sm"
																x-data="{count: {{ organization()->unreadChatsCount() }}}"
																x-text="count"
																x-cloak
																x-show="count > 0"	
															></span> --}}
														</a>
													</li>
												@endif
												@if (Route::has('organization.edit'))
													<li>
														<a class="submenu-user__link" href="{{ localize_route('organization.edit') }}">
															{{ __('header.personal.edit_organization') }}
														</a>
													</li>
												@endif
											</ul>
										</x-header.submenu>
									@else
										<x-header.submenu>
											<div class="text-center text-accent">
												{{ __('header.personal.email_not_verified') }}
											</div>
											<form method="POST" action="/email/verification-notification">
												@csrf
												<button class="button button_primary">{{ __('header.personal.resend_btn') }}</button>
											</form>
										</x-header.submenu>
									@endif
									
									<x-header.submenu>
										<form action="{{ localize_route('logout') }}" method="POST">
											@csrf
											<button class="submenu-user__link">@lang('header.logout')</button>
										</form>
									</x-header.submenu>
								</div>
							</div>
						</div>
					</div>
					<button type="button" class="menu__icon icon-menu"><span></span></button>
				@else
					<button type="button" class="menu__icon icon-menu"><span></span></button>
					<div class="header__btns" data-da=".menu__body, 991.98">
						<a href="{{ localize_route('register') }}" class="header__btn button">@lang('header.register')</a>
						<a href="{{ localize_route('login') }}" class="header__btn button button_primary">@lang('header.login')</a>
					</div>
				@endauth
            </div>
        </div>
    </div>
    <div class="header__bottom">
        <div class="header__container">
            <div class="header__menu menu">
                <nav class="menu__body">
                    <ul class="menu__list">
						@if (Route::has('home'))
                        	<li class="menu__item _active"><a href="{{ localize_route('home') }}" class="menu__link"><span>@lang('header.home')</span></a></li>
						@endif
                        <li class="menu__item" data-spoilers="991.98">
                            <button class="menu__link" type="button" data-spoiler>
                                <span class="_icon-arrow">@lang('header.subject')</span>
                            </button>
                            <ul class="menu__submenu submenu">
								@foreach (subjects() as $subject)
									<li class="submenu__item">
										<a href="{{ localize_route('subject', $subject->slug) }}" class="submenu__link tw-text-right">
											{{ $subject->{'title_'.loc()} }}
										</a>
									</li>
								@endforeach
                            </ul>
                        </li>
						@if (Route::has('archive'))
                        	<li class="menu__item _active"><a href="{{ localize_route('archive') }}" class="menu__link"><span>@lang('header.archive')</span></a></li>
						@endif
						@if (Route::has('about'))
                        	<li class="menu__item _active"><a href="{{ localize_route('about') }}" class="menu__link"><span>@lang('header.about')</span></a></li>
						@endif
						@if (Route::has('contacts'))
                        	<li class="menu__item _active"><a href="{{ localize_route('contacts') }}" class="menu__link"><span>@lang('header.contacts')</span></a></li>
						@endif
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</header>
