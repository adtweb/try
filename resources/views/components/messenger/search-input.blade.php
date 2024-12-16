<div class="tw-relative tw-w-full tw-flex tw-items-center" x-data="searchInput"
	@start-chat.window="$refs.input.value = ''"
>
    <input type="text" class="input tw-pr-8" placeholder="{{ __('pages.messenger.search') }}" @input.debounce="search" x-ref="input">
    <div class="tw-absolute tw-right-2">
		<button class="tw-flex tw-items-center" x-show="mode === 'chats'" @click="search" x-cloak>
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
				stroke-width="1.5" stroke="currentColor" class="tw-w-6 tw-h-6">
				<path stroke-linecap="round" stroke-linejoin="round"
					d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
			</svg>
		</button>

        <x-loader xShow="mode === 'search' && searchLoading" class="tw-w-6 tw-h-6" :transition="false" />

        <button class="tw-flex tw-items-center" x-show="mode === 'search' && !searchLoading" @click="closeSearch" x-cloak>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="tw-w-6 tw-h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<script>
	document.addEventListener('alpine:init', () => {
		Alpine.data('searchInput', () => ({
			searchLoading: false,
			
			search() {
				if (this.$refs.input.value.trim() === '') {
					this.mode = 'chats'
					return
				}
				
				this.mode = 'search'
				this.searchLoading = true
				
				axios
					.get(route('chats.search'), {params: {search: this.$refs.input.value}})
					.then(resp => {
						this.searchResult = resp.data
					})
					.catch(err => this.$store.toasts.handleResponseError(err))
					.finally(() => this.searchLoading = false)
			},
			closeSearch() {
				this.searchResult = []
				this.mode = 'chats'
				this.$refs.input.value = ''
			},
		}))
	})
</script>
