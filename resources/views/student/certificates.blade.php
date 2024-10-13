<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto {{ Auth::user()->user_type === 'Admin' ? 'p-4 md:ml-64 h-auto pt-20' : '' }}">
        <div class="py-8 antialiased md:py-8">
            @forelse ($certificates as $certificate)
                <div
                    class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex items-center">
                    <div class="flex items-center">
                        <img src="{{ asset("storage/users-avatar/{$certificate->student?->avatar}") }}"
                            alt="Profile Picture" class="w-10 h-10 rounded-full ml-4 object-cover">
                        <p class="ml-4 text-lg">{{ $certificate->lesson?->title }} <br> <span
                                class="text-xs font-normal">Given:
                                {{ $certificate->created_at->format('F d, Y') }}</span></p>
                    </div>
                    <a href="{{ route('profile.certificates.view', [$user, $certificate]) }}"
                        class="ml-auto rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        View Certificate
                    </a>
                </div>
            @empty
                <section class="bg-white dark:bg-gray-900">
                    <div class="py-8 px-4 mx-auto max-w-screen-md text-center lg:py-16 lg:px-12">
                        <svg class="mx-auto mb-4 w-12 h-12 text-gray-700 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8 8-4 4 4 4m8 0 4-4-4-4m-2-3-4 14" />
                        </svg>
                        <h1
                            class="mb-4 text-4xl font-bold tracking-tight leading-none text-gray-900 lg:mb-6 dark:text-white">
                            No Certificates Yet
                        </h1>
                    </div>
                </section>
            @endforelse
        </div>
    </main>
</x-app-layout>
