<div class="tw-flex tw-flex-col tw-h-full tw-bg-[#eeeeee] tw-basis-1/4 tw-grow-0 tw-max-w-[25%]" x-data="chatsList">
	<div class="tw-px-3 tw-flex tw-items-center tw-justify-center tw-basis-16 tw-shrink-0" style="border-bottom: 1px solid #cccccc">
		<x-messenger.search-input />
	</div>

	{{-- search result --}}
	<div class="tw-flex tw-flex-col tw-h-full" x-show="mode === 'search'">
		<template x-for="participant in searchResult">
			<div class="tw-p-2 hover:tw-bg-[#bbb] tw-cursor-pointer tw-transition" @click="startPersonalChat(participant.id)">
				<span x-text="participant.name_ru + ' ' + participant.surname_ru"></span>
			</div>
		</template>
	</div>

	{{-- chat list --}}
	<div class="" x-show="mode === 'chats'">
		<template x-for="chat in chats">
			<div class="tw-p-2 hover:tw-bg-[#bbb] tw-cursor-pointer tw-transition tw-min-h-[51px]" 
				:class="chat.id === activeChat?.id && '!tw-bg-[#1e4759] !tw-text-[#fff]'"
				@click="openChat(chat)"
			>
				<div class="">
					<template x-if="getCollocutor(chat)?.role === 'organizer'">
						<div>
							<div class="tw-flex tw-items-center tw-gap-1">
								<span :title="chat.conference?.title_{{ loc() }} + `\n{{ __('pages.messenger.organizer') }}`"><x-messenger.organizer-icon /></span>

								<span x-text="getCollocutor(chat)?.name_{{ loc() }} + ' ' + getCollocutor(chat)?.surname_{{ loc() }}"></span>
							</div>
						</div>
					</template>
					<template x-if="getCollocutor(chat)?.role === 'moderator'">
						<div>
							<div class="tw-flex tw-items-center tw-gap-1">
								<span :title="chat.conference?.title_{{ loc() }} + `\n{{ __('pages.messenger.moderator') }}`"><x-messenger.organizer-icon /></span>

								<span x-text="getCollocutor(chat)?.name_{{ loc() }} + ' ' + getCollocutor(chat)?.surname_{{ loc() }}"></span>
							</div>
						</div>
					</template>
					<template x-if="getCollocutor(chat)?.role === 'participant'">
						<span x-text="getCollocutor(chat)?.name_{{ loc() }} + ' ' + getCollocutor(chat)?.surname_{{ loc() }}"></span>
					</template>
				</div>
				<div class="tw-flex tw-justify-between tw-items-center tw-gap-2">
					<span class="tw-flex tw-gap-1 tw-leading-normal tw-truncate">
						<span 
							x-show="chat.data.last_message?.participant_id === user.participant.id"
						>
							{{ __('pages.messenger.you') }}:
						</span>
						<span class="tw-truncate" x-text="chat.data.last_message?.text"></span>
					</span>
					<span class="tw-text-nowrap" x-text="formatMessageTime(chat.data.last_message?.created_at)"></span>
				</div>
			</div>
		</template>
		<template x-if="chats.length === 0">
			<div class="tw-p-2 tw-text-center">{{ __('pages.messenger.no_chats') }}</div>
		</template>
	</div>
</div>

<script>
	document.addEventListener('alpine:init', () => {
		Alpine.data('chatsList', () => ({
			searchResult: [],

			init() {
				axios
					.get(route('chats.index'), {params: {conference_id: this.conferenceId, role: this.role}})
					.then(resp => {
						this.chats = resp.data

						const url = new URL(window.location.href);
						const method = url.searchParams.get('method');

						if (method === 'startChatWithParticipant') {
							if (url.searchParams.get('participantId') === null) {
								this.$store.toasts.pushError('Не удалось создать чат. Не указан пользователь', 20000)
								return;
							}

							this.startPersonalChat(url.searchParams.get('participantId'))
						} else if (method === 'startChatWithOrganization') {
							if (this.conferenceId) {
								this.$store.toasts.pushError('Нельзя начать чат с другим организатором', 20000)
								return
							}

							axios
								.post(route(
									'chats.start.organization', 
									{conference_id: url.searchParams.get('conferenceId')}
								))
								.then(response => {
									const newChat = response.data
									this.handleChat(newChat)
								})
								.catch(error => {
									if (error.response.status === 422) {
										this.$store.toasts.handleResponseError(error)
									}
									console.error(error)
								})

						} else if (method === 'startChatWithModerator') {
							if (this.conferenceId) {
								this.$store.toasts.pushError('Нельзя начать чат с модератором', 20000)
								return
							}

							axios
								.post(route(
									'chats.start.moderator', 
									{
										conference_id: url.searchParams.get('conferenceId'),
										user_id: url.searchParams.get('userId')
									}
								))
								.then(response => {
									const newChat = response.data
									this.handleChat(newChat)
								})
								.catch(error => {
									this.$store.toasts.handleResponseError(error)
								})
						} else if (method === 'openChat') {
							let chat = this.chats.find(el => el.id == url.searchParams.get('chatId'))

							this.openChat(chat)
						}


					})
					.catch(err => this.$store.toasts.handleResponseError(err))
			},
			startPersonalChat(participantId) {
				this.$dispatch('start-chat')

				axios
					.post(route('chats.store'), {
						participant_id: participantId,
						conference_id: this.conferenceId,
					})
					.then(resp => {
						const newChat = resp.data
						this.handleChat(newChat)
					})
					.catch(err => this.$store.toasts.handleResponseError(err))
			},
			openChat(chat) {
				if (this.activeChat?.id === chat.id) {
					return;
				}
				
				this.activeChat = chat
				this.messages = []
				
				axios
					.get(route('chats.messages.index', chat.id))
					.then(resp => {
						this.messages = resp.data.data.reverse()
						this.scrollMessagesDown()

						this.markAsRead(chat)
					})
					.catch(err => this.$store.toasts.handleResponseError(err))
			},
			handleChat(newChat) {
				this.mode = 'chats'
						
				if (this.chats.some(chat => chat.id === newChat.id)) {
					this.openChat(newChat)
					return
				}
				
				this.activeChat = newChat
				this.chats.unshift(newChat)
				this.messages = [];
			},
			markAsRead(chat) {
				axios
					.put(route('chats.read', chat.id), {
						role: this.role,
					})
			},
		}))
	})
</script>
