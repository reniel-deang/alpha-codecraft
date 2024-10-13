<x-guest-layout>
    @include('layouts.navbar')
    <main class="p-16 md:min-h-screen">
        <section class="bg-cover bg-right bg-no-repeat bg-gray-700 bg-blend-multiply bg-local rounded-2xl"
            style="background-image: url('{{ asset('images/banner-bg.jpg') }}')">
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
                        <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-10 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white py-10">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-6">About CodeCraft</h2>
                <p class="text-lg text-center mb-8">
                    CodeCraft is designed to enhance the programming learning experience through personalized
                    coaching and adaptive learning paths. Students can connect with experienced instructors via
                    video conferences, ensuring they receive tailored guidance and support.
                </p>
                <p class="text-lg text-center mb-8">
                    Instructors can easily showcase their availability, allowing students to view and select suitable
                    time slots for their sessions. Additionally, our community forum fosters collaboration and knowledge
                    sharing among students, encouraging engagement and mutual support. With access to a diverse range of
                    instructors specializing in various programming languages and teaching methods, learners can find
                    the
                    perfect match for their educational journey.
                </p>
            </div>
        </section>

        <section class="py-16 bg-gray-900 text-white">
            <div class="px-4 mx-auto max-w-screen-xl">
                <h2 class="text-4xl font-bold mb-8 text-center">What Our Users Say</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Testimony 1 -->
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                        <p class="text-lg italic mb-4 text-gray-300">"CodeCraft helped me land my first developer job!
                            The mentors are fantastic and really care about your growth."</p>
                        <h3 class="font-bold text-lg text-white">— John Cruz</h3>
                    </div>
                    <!-- Testimony 2 -->
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                        <p class="text-lg italic mb-4 text-gray-300">"I went from knowing nothing about code to building
                            my first app in just a few months. Highly recommend CodeCraft!"</p>
                        <h3 class="font-bold text-lg text-white">— Jane Smith</h3>
                    </div>
                    <!-- Testimony 3 -->
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                        <p class="text-lg italic mb-4 text-gray-300">"The community and support at CodeCraft is amazing.
                            They provide resources and constant feedback which helped me improve a lot."</p>
                        <h3 class="font-bold text-lg text-white">— Maria Johnson</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white rounded-lg shadow dark:bg-gray-900">
        <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <a href="{{ config('app.url') }}" class="flex justify-center items-center text-2xl font-semibold text-gray-900 dark:text-white">
                    <x-application-logo class="mr-3 h-8 text-black dark:text-slate-400" />
                    <div class="leading-tight tracking-tight font-bold">
                        <span class="text-gray-800 dark:text-gray-300">CODE</span>
                        <span class="text-pink-300">CRAFT</span>
                    </div>
                </a>
                <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                    <li>
                        <a href="{{ route('be.a.teacher') }}" class="hover:underline me-4 md:me-6">Be a Teacher</a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="hover:underline me-4 md:me-6">Login</a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="hover:underline me-4 md:me-6">Register</a>
                    </li>
                    <li>
                        <a href="{{ route('forum.category.index') }}" class="hover:underline">Connect</a>
                    </li>
                </ul>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                &copy; 2024 <a href="{{ config('app.url') }}" class="hover:underline">CodeCraft</a>. All Rights Reserved.
            </span>
        </div>
    </footer>
</x-guest-layout>
