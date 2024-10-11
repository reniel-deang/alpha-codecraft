<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-8">
            @foreach ($certificates as $certificate)
                <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex items-center">
                    <div class="flex items-center">
                        <img src="{{ asset("storage/users-avatar/{$certificate->student?->avatar}") }}"
                            alt="Profile Picture" class="w-10 h-10 rounded-full ml-4 object-cover">
                        <p class="ml-4 text-lg">{{ $certificate->lesson?->title }} <br> <span class="text-xs font-normal">Given: {{ $certificate->created_at->format('F d, Y') }}</span></p>
                    </div>
                    <a href="{{ route('profile.certificates.view', [$user, $certificate]) }}"
                        class="ml-auto rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        View Certificate
                    </a>
                </div>
            @endforeach
        </div>
    </main>
</x-app-layout>
