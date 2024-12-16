<div class="tw-w-full tw-h-[calc(100vh-84px-68px-50px)] tw-flex tw-gap-1" x-data="messenger">
	<x-messenger.chats-list />
	
	<div class="tw-flex tw-flex-col tw-h-full tw-bg-[#eeeeee] tw-grow">
		<div class="tw-px-3 tw-flex tw-items-center tw-justify-between tw-align-center tw-gap-2 tw-h-16" style="border-bottom: 1px solid #cccccc">
			<div>
				<template x-if="getCollocutor(activeChat)?.role === 'participant'">
					<div>
						<span x-text="getCollocutor(activeChat)?.name_{{ loc() }}"></span>
						<span x-text="getCollocutor(activeChat)?.surname_{{ loc() }}"></span>
					</div>
				</template>
				<template x-if="getCollocutor(activeChat)?.role === 'organizer'">
					<div>
						<div>
							<span x-text="getCollocutor(activeChat)?.name_{{ loc() }}"></span>
							<span x-text="getCollocutor(activeChat)?.surname_{{ loc() }}"></span>
						</div>
						<div>
							<span x-text="activeChat.conference?.title_{{ loc() }}"></span>
						</div>
						<div>{{ __('pages.messenger.organizer') }}</div>
					</div>
				</template>
				<template x-if="getCollocutor(activeChat)?.role === 'moderator'">
					<div>
						<div>
							<span x-text="getCollocutor(activeChat)?.name_{{ loc() }}"></span>
							<span x-text="getCollocutor(activeChat)?.surname_{{ loc() }}"></span>
						</div>
						<div>
							<span x-text="activeChat.conference?.title_{{ loc() }}"></span>
						</div>
						<div>{{ __('pages.messenger.moderator') }}</div>
					</div>
				</template>
			</div>
			@isset ($conference)
				<div class="tw-text-[#e25553]">
					{{ __('pages.messenger.org_chat') }}
				</div>
			@endisset
		</div>

		<div class="tw-flex-1 tw-overflow-auto tw-p-2 tw-flex tw-flex-col tw-gap-2" x-show="activeChat" x-ref="messages">
			<template x-for="message in messages">
				<div class="tw-p-3 tw-rounded tw-max-w-[50%] tw-min-w-[100px] tw-grow-0 tw-w-fit tw-leading-snug" 
					:class="message?.participant_id === user.participant.id
						? 'tw-bg-[#c8f5c2]' 
						: 'tw-bg-[#f8f5f2]'"
					>
					<div class="tw-mb-2" x-html="formatMessage(message?.text)"></div>
					<div class="tw-text-right tw-text-xs tw-text-[#999]" x-text="formatMessageTime(message?.created_at)"></div>
				</div>
			</template>
		</div>

		<div class="tw-relative" x-show="activeChat">
			<textarea 
				class="input tw-bg-[#f8f5f2] !tw-pr-8" 
				@keydown.enter.ctrl.prevent="$el.value += '\n'"
				@keydown.enter.shift.prevent="$el.value += '\n'"
				@keydown.enter.prevent="sendMessage" 
				x-ref="message"
			></textarea>

			<button class="tw-absolute tw-top-0 tw-bottom-0 tw-right-0 tw-flex tw-items-center tw-justify-center tw-p-1" @click="sendMessage">
				<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" class="tw-w-6 tw-h-6" version="1.1" x="0px" y="0px" enable-background="new 0 0 24 24"><path fill="currentColor" d="M1.101,21.757L23.8,12.028L1.101,2.3l0.011,7.912l13.623,1.816L1.112,13.845 L1.101,21.757z"></path></svg>
			</button>
		</div>
	</div>
</div>

<script>
	document.addEventListener('alpine:init', () => {
		Alpine.data('messenger', () => ({
			user: @json(auth()->user()->load(['participant'])),
			conferenceId: {{ $conference?->id ?? 'null' }},
			role: '{{ $conference?->id ? $role : 'participant' }}',
			mode: 'chats',
			chats: [],
			activeChat: null,
			messages: [],
			
			init() {
				this.$nextTick(() => {
					Echo.private(`App.Models.User.${this.user.id}`)
						.listen('.message.created', (e) => {
							if (e.from.user_id !== this.user.id) {
								if (this.role !== e.to) {
									return;
								}
								if (this.role === 'organization' && this.conferenceId != e.message.conference_id) {
									return;
								}
								if (this.role === 'moderator' && this.conferenceId != e.message.conference_id) {
									return;
								}
							}

							if (e.message.chat_id === this.activeChat?.id) {
								this.messages.push(e.message)
							}
							this.addNewMessage(e.message)
							this.scrollMessagesDown()
						})
				});
			},
			getCollocutor(chat) {
				if (!chat) return
				
				return chat.data.members.find(member => member.user_id !== this.user.id)
			},
			sendMessage(event) {
				if (event.shiftKey || event.ctrlKey) {
					return;
				}
				if (this.$refs.message.value.trim() === '') return

				axios
					.post(route('chats.messages.store', this.activeChat.id), {
						text: this.$refs.message.value,
					})

				this.$refs.message.value = ''
			},
			formatMessageTime(time) {
				if (!time) return ''

				let dt = DateTime.fromISO(time).setLocale('{{ loc() }}')
				let dayStart = DateTime.now().set({hour: 0, minute: 0, second: 0, millisecond: 0})

				if (dt < dayStart) {
					return dt.toLocaleString(DateTime.DATE_SHORT)
				} else {
					return dt.toLocaleString(DateTime.TIME_SIMPLE)
				}
			},
			formatMessage(text) {
				return text.replaceAll('\n', '<br>');
			},
			scrollMessagesDown() {
				this.$nextTick(() => {
					this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight
				})	
			},
			addNewMessage(message) {
				let chat = this.chats.find(chat => chat.id === message.chat_id)

				if (chat) {
					chat.data.last_message = message
					
					const index = this.chats.indexOf(chat);
					this.chats.splice(index, 1);
					this.$nextTick(() => {
						this.chats.unshift(chat);
					})
					
					return
				}

				this.chats.unshift(message.chat)
				this.chats[0].data.last_message = message
			}
		}))
	})
</script>
