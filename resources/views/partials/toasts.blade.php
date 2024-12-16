<div class="tw-fixed tw-right-2 tw-bottom-2 tw-w-[300px] tw-flex tw-flex-col tw-gap-2 tw-z-50" x-data="toasts">
	<template x-for="toast in $store.toasts.toasts">
		<div class="tw-p-2" :class="$store.toasts.styles(toast)" style="border: 1px solid #f8f5f2">
			<div class="tw-flex tw-justify-between tw-items-start tw-gap-2 tw-mb-2">
				<div class="tw-font-bold" x-text="toast.title"></div>
				<button class="tw-flex tw-justify-center tw-items-center" @click="$store.toasts.remove(toast.id)">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="tw-size-5"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" /></svg>
				</button>
			</div>
			<div class="tw-leading-tight tw-line-clamp-5" x-text="toast.message"></div>
			<div class="tw-mt-4">
				<template x-if="toast.link">
					<a class="tw-underline tw-underline-offset-2" :href="toast.link">Подробнее</a>
				</template>
			</div>
		</div>
	</template>
</div>

<script>
	document.addEventListener('alpine:init', () => {
		Alpine.data('toasts', () => ({
			init() {
				this.$nextTick(() => {
					window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
						.listen('.message.created', (e) => {
							// console.log(e);
							
							if (route().current('chats.view') || route().current('conference.messenger')) {
								return;
								// if (this.role !== e.to) {
								// 	return;
								// }
								// if (this.role === 'organization' && this.conferenceId !== e.message.conference_id) {
								// 	return;
								// }
								// if (this.role === 'moderator' && this.conferenceId !== e.message.conference_id) {
								// 	return;
								// }
							}

							if (e.to === 'organizer' || e.to === 'moderator') {
								this.$store.toasts.pushInfo(
									e.message.text, 
									null, 
									'Новое сообщение организатору', 
									route('conference.messenger', {
										conference: e.message.conference_slug,
										method: 'openChat',
										chatId: e.message.chat_id,
									})
								)
								return
							}
							
							this.$store.toasts.pushInfo(
								e.message.text, 
								20000, 
								'Новое сообщение участнику', 
								route('chats.view', {
									method: 'openChat',
									chatId: e.message.chat_id,
								})
							)
						})
				});
			},
		}))
	})
</script>
