@extends('classroom.classroom')

@section('participants')
<!-- Post Button Container -->
<div class="flex justify-end my-3">
    @if ($class->conference)
        <a href="{{ route('classes.meet.start', [$class, $class->conference]) }}"
            class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="inline-block w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            Go to Meeting
        </a>
    @else
        @if (Auth::user()->user_type === 'Teacher')
            <button onclick="newMeet(this)" data-link="{{ route('classes.meet.create', $class) }}"
                class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                New Meeting
            </button>
        @else
            <button type="button"
                class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="inline-block w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
                No Meeting Yet
            </button>
        @endif
    @endif
</div>

<!-- Container for the layout -->
<div class="container mx-auto p-6">

    <!-- Teachers Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Teacher</h2>
        </div>
        <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex items-center">
            <div class="flex items-center">
                <img src="{{ asset("storage/users-avatar/{$class->teacher?->avatar}") }}" alt="Profile Picture" class="w-10 h-10 rounded-full ml-4 object-cover">
                <p class="ml-4">{{ $class->teacher?->name }}</p>
            </div>
        </div>
    </div>

    <!-- Students Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Students</h2>
        </div>
        @if ($enrolled->count() > 0)
            @foreach ($enrolled as $student )
                <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex items-center mb-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <img src="{{ asset("storage/users-avatar/{$student->avatar}") }}" alt="Profile Picture" class="w-10 h-10 rounded-full ml-4 object-cover">
                            <p class="ml-4">{{ $student->name }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex justify-center items-center">
                <p>No students enrolled yet</p>
            </div>
        @endif
    </div>

</div>
    
@endsection