<x-guest-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:min-h-screen lg:py-0">
            <a href="/" class="flex items-center mb-6 text-4xl font-semibold">
                <x-application-logo class="w-14 h-14 text-black dark:text-slate-400" />
                <div class="leading-tight tracking-tight font-bold">
                    <span class="text-gray-800 dark:text-gray-300">CODE</span>
                    <span class="text-pink-300">CRAFT</span>
                </div>
            </a>
            <div class="w-full rounded-lg dark:border md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight md:text-2xl text-gray-900 dark:text-white">
                        Wait for Admin Approval
                    </h1>
                    <p class="mb-4 text-md text-gray-900 dark:text-white">
                        Thanks for signing up! Before you get started, please wait for your account approval. An admin needs to verify your registration details first. We will notify you of your status once it's done.
                    </p>
                    <p class="mb-4 text-md text-gray-900 dark:text-white">
                        In the meantime, you are welcome to connect and browse our forum to read and see meaningful discussions.
                    </p>
                    <button type="button" onclick="window.location=`{{route('forum.category.index')}}`" class="w-full py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Go to forum
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
            
                        <button class="w-full py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
