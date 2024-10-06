<x-app-layout>
    @if (Auth::user()->user_type === 'Student')
        <main class="py-16 px-6 md:p-16 md:min-h-screen">
            <section class="bg-white dark:bg-gray-900">
                <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6 ">
                    <div class="mx-auto max-w-screen-sm text-center mb-8 lg:mb-16">
                        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                            Find a Teacher
                        </h2>
                        <p class="font-light text-gray-500 lg:mb-16 sm:text-xl dark:text-gray-400">
                            Explore the diverse list of educators and teaching styles to find the perfect mentor who
                            aligns with your learning goals.
                        </p>
                    </div>

                    @if ($teachers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($teachers as $teacher)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                                    <img class="w-24 h-24 rounded-full mx-auto"
                                        src="{{ asset("storage/users-avatar/{$teacher->avatar}") }}" alt="Bonnie Green">
                                    <div class="text-center mt-4">
                                        <h2 class="text-lg font-bold dark:text-white">{{ $teacher->name }}</h2>

                                        <p class="my-4 text-sm dark:text-gray-400 truncate">
                                            <button data-popover-target="popover-{{ $teacher->id }}" data-popover-trigger="click"
                                                data-popover-placement="right" type="button"><svg
                                                    class="w-4 h-4 ms-2 text-gray-400 hover:text-gray-500"
                                                    aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd"></path>
                                                </svg><span class="sr-only">Show information</span></button>
                                            {{ $teacher->teacherDetail?->bio ?? 'This teacher has not set their bio yet.' }}
                                        </p>

                                        <div data-popover id="popover-{{ $teacher->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-96 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-3 space-y-2">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                                    Bio
                                                </h3>
                                                <p class="whitespace-pre-line">
                                                    {{ $teacher->teacherDetail?->bio ?? 'This teacher has not set their bio yet.' }}
                                                </p>
                                            </div>
                                            <div data-popper-arrow></div>
                                        </div>

                                        <div class="flex justify-center mt-4 space-x-2">
                                            <a href="{{ route('profile', $teacher) }}" class="text-gray-400 hover:text-gray-500 dark:hover:text-white">
                                                <svg class="inline-block h-4 w-4 stroke-current" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961" />
                                                </svg>
                                                View Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <section class="bg-white dark:bg-gray-900">
                            <div class="py-8 px-4 mx-auto max-w-screen-md text-center lg:py-16 lg:px-12">
                                <svg class="mx-auto mb-4 w-12 h-12 text-gray-700 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m8 8-4 4 4 4m8 0 4-4-4-4m-2-3-4 14" />
                                </svg>
                                <h1
                                    class="mb-4 text-4xl font-bold tracking-tight leading-none text-gray-900 lg:mb-6 dark:text-white">
                                    No Teachers Yet
                                </h1>
                                <p class="font-light text-gray-500 md:text-lg xl:text-xl dark:text-gray-400">
                                    No teachers are currently registered or verified. Please check back later.
                                </p>
                            </div>
                        </section>
                    @endif

                </div>
            </section>
        </main>
    @endif
</x-app-layout>
