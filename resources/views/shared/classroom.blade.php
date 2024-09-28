<x-app-layout>
    <main class="p-16 md:min-h-screen">
        @if (Auth::user()->user_type === 'Student')
            @include('student.classroom-list')
        @elseif (Auth::user()->user_type === 'Teacher')
            @include('teacher.classroom-list')
        @endif
    </main>
</x-app-layout>
