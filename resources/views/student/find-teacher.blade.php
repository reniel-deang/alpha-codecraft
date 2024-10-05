<x-app-layout>
    @if(Auth::user()->user_type === 'Student')
        <main class="py-16 px-6 md:p-16 md:min-h-screen">
            
        </main>
    @endif
</x-app-layout>