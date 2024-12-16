<x-mail.layout.layout>

	{{-- Header --}}
	<x-slot:header>
	<x-mail.layout.header :url="config('app.url')">
	{{ config('app.name') }}
	</x-mail.layout.header>
	</x-slot:header>
	
	{{-- Body --}}
	{!! $slot !!}
	
	{{-- Subcopy --}}
	@isset($subcopy)
	<x-slot:subcopy>
	<x-mail.layout.subcopy>
	{{ $subcopy }}
	</x-mail.layout.subcopy>
	</x-slot:subcopy>
	@endisset
	
	{{-- Footer --}}
	<x-slot:footer>
	<x-mail.layout.footer>
	© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
	</x-mail.layout.footer>
	</x-slot:footer>
</x-mail.layout.layout>
