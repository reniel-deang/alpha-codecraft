<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-8">
            <div class="relative block max-w-full min-h-48 p-6 bg-white border border-gray-200 rounded-t-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <button id="back-button" onclick="backToLessons(this)" data-link="{{ route('classes.view.lessons', $class) }}"
                    class="absolute right-6 inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Back to lessons
                </button>
                <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $lesson->title }}
                </h4>
                <p class="font-normal text-gray-700 dark:text-gray-400 mt-3">
                    {{ $lesson->description }}
                </p>
            </div>
        </div>
    </main>

    @push('scripts')
        
    @endpush
</x-app-layout>