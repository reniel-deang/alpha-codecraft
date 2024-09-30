<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
    <div class="flex items-center justify-between mr-4 text-lg">
        <x-application-logo class="mr-3 h-8 text-black dark:text-slate-400" />
        <div class="leading-tight tracking-tight font-bold">
            <span class="text-gray-800 dark:text-gray-300">CODE</span>
            <span class="text-pink-300">CRAFT</span>
        </div>
    </div>
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
