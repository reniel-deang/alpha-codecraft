<x-guest-layout>
    @include('layouts.navbar')
    <main class="p-16 md:min-h-screen">
        <section class="bg-cover bg-right bg-no-repeat bg-gray-700 bg-blend-multiply bg-local rounded-2xl" style="background-image: url('{{ asset('images/banner-bg.jpg') }}')">
            <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-36">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
                    Welcome to CodeCraft </h1>
                <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">
                    <span class="font-bold">Code<span class="text-pink-300">Craft</span></span>
                     is an online platform for Programming with personal programming coaches.
                </p>
                <p class="mb-8 text-lg font-bold text-pink-300 lg:text-xl sm:px-16 lg:px-48">
                    Let's Dive Into The World of Programming.
                </p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                    <a href="{{ route('login') }}"
                        class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                        Get started
                        <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </main>
</x-guest-layout>
