<x-app-layout>
    <main class="p-16 {{ Auth::user()->user_type === 'Admin' ? 'p-4 md:ml-64 h-auto pt-20' : '' }}">
        <div class="p-8 bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 text-gray-700 dark:text-gray-200 shadow mt-24">
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="grid grid-cols-3 text-center order-last md:order-first mt-20 md:mt-0">
                    <div>
                        <p class="font-bold text-gray-700 dark:text-gray-200 text-xl">22</p>
                        <p class="text-gray-400">Moments</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 dark:text-gray-200 text-xl">10</p>
                        <p class="text-gray-400">Certificates</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 dark:text-gray-200 text-xl">89h</p>
                        <p class="text-gray-400">Talktime</p>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset('storage/users-avatar/'.Auth::user()->avatar) }}"
                     alt="" class="w-48 h-48 bg-indigo-100 mx-auto rounded-full shadow-2xl absolute inset-x-0 top-0 -mt-24 flex items-center justify-center text-indigo-500">
                </div>

                <div class="space-x-8 flex justify-between mt-32 md:mt-0 md:justify-center">
                    <button
                        class="invisible text-white py-2 px-4 uppercase rounded bg-blue-400 hover:bg-blue-500 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                        Message
                    </button>
                    <button
                        class="text-white py-2 px-4 uppercase rounded bg-gray-700 hover:bg-gray-800 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5
                            dark:text-white dark:bg-blue-400 dark:hover:bg-blue-500 ">
                        Edit Profile
                    </button>
                </div>
            </div>

            <div class="mt-20 text-center border-b pb-12">
                <h1 class="text-4xl font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</h1>
                @php
                    $address = '';
                    if(Auth::user()->user_type === 'Student') {
                        $address = Auth::user()->studentDetail->address;
                    } elseif(Auth::user()->user_type === 'Teacher') {
                        $address = Auth::user()->teacherDetail->address;
                    }
                @endphp
                <p class="font-medium text-xl text-gray-600 dark:text-gray-200 mt-3">{{ $address }}</p>
            </div>

            <div class="my-6 md:w-3/4 md:mx-auto">
                <h3 class="text-center font-semibold mb-3">Bio</h3>
                <p class="text-gray-700 dark:text-gray-200 text-center font-light lg:px-16">
                    Lorem ipsum
                </p>
            </div>

        </div>
    </main>
</x-app-layout>
