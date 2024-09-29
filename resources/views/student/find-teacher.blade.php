<x-app-layout>
    @if(Auth::user()->user_type === 'Teacher')
    @endif
</x-app-layout>