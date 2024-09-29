<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-16">
            <div
                class="relative block max-w-full min-h-48 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $class->name }}
                </h4>
                <span class="mb-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white">
                    {{ $class->subject }}
                </span>
                <p class="font-normal text-gray-700 dark:text-gray-400 mt-3">
                    {{ $class->description }}
                </p>

                <div class="{{ Auth::user()->user_type === 'Student' ? 'hidden' : 'absolute' }} right-0 bottom-0 w-1/4">
                    <button data-modal-target="show-code" data-modal-toggle="show-code"
                        class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Code
                    </button>
                    <button onclick="inviteStudent(this)" data-id="{{ $class->id }}"
                        class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Invite
                    </button>
                </div>
            </div>

        </div>
    </main>


    <!-- Code modal -->
    <div id="show-code" tabindex="-1" data-modal-backdrop="static" 
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button id="show-code" type="button" data-modal-hide="show-code" 
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <h2 class="my-10 text-5xl font-bold text-gray-500 dark:text-gray-400">
                        {{ $class->code }}
                    </h2>
                    <button data-modal-hide="show-code" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
