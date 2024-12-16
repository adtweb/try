<footer class="footer">
	<div class="footer__container">
		<div class="tw-flex tw-justify-between">
			<div class="tw-flex tw-flex-col tw-gap-6">
				<a href="{{ route('home') }}" class="logo">
					<span>ucp</span>
					<span>Universal Conference Portal</span>
				</a>
				<a class="tw-text-[#fff]" href="/policy_{{ loc() }}.pdf" download>{{ __('footer.policy') }}</a>
			</div>
		</div>
	</div>
</footer>
