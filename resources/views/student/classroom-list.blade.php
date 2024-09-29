<div class="py-8 antialiased md:py-16">
    @if (Auth::user()->has('enrollments')->count() > 0)
        <section class="bg-white dark:bg-gray-900">
            <button onclick="joinClass(this)"
                class="float-right mx-5 my-2 block text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                type="button">
                Join Class
            </button>

            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($enrollments as $classroom)
                        <div
                            class="relative block max-w-sm h-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-900">
                            <div class="flex justify-start">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $classroom->name }}
                                </h5>
                                <div class="ms-auto w-1/4">
                                    <button id="dropdown-button-{{ $classroom->id }}"
                                        data-dropdown-toggle="dropdown-{{ $classroom->id }}"
                                        class="inline-block float-right text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5"
                                        type="button">
                                        <span class="sr-only">Open dropdown</span>
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 16 3">
                                            <path
                                                d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                                        </svg>
                                    </button>
                                    <!-- Dropdown menu -->
                                    <div id="dropdown-{{ $classroom->id }}"
                                        class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                        <ul class="py-2" aria-labelledby="dropdown-button-{{ $classroom->id }}">
                                            <li>
                                                <button data-id="{{ $classroom->id }}"
                                                    data-link="{{ route('classes.leave', $classroom) }}"
                                                    id="delete-modal-{{ $classroom->id }}" onclick="leaveClass(this)"
                                                    class="block w-full text-start px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                    Leave Class
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <h6 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                                {{ $classroom->subject }}
                            </h6>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 w-1/2 truncate">
                                {{ $classroom->description }}
                            </p>
                            <a href="{{ route('classes.view', $classroom) }}"
                                class="absolute mt-24 bottom-0 right-0 m-5 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Go to Class
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <div class="mx-auto grid max-w-screen-xl px-4 pb-8 md:grid-cols-12 lg:gap-12 lg:pb-16 xl:gap-0">
            <div class="content-center justify-self-start md:col-span-7 md:text-start">
                <p class=" max-w-2xl text-gray-500 dark:text-gray-400 md:mb-12 md:text-lg mb-3 lg:mb-5 lg:text-xl"
                    id="landing-text">
                    You have not joined any classes yet
                </p>
                <button onclick="joinClass(this)"
                    class="inline-block rounded-lg bg-primary-700 px-6 py-3.5 text-center font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Join Class
                </button>
            </div>
            <div class="hidden md:col-span-5 md:mt-0 md:flex">
                <img class="dark:hidden"
                    src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/girl-shopping-list.svg"
                    alt="shopping illustration" />
                <img class="hidden dark:block"
                    src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/girl-shopping-list-dark.svg"
                    alt="shopping illustration" />
            </div>
        </div>
    @endif
</div>


<!-- Main modal -->
<div id="join-class-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Join Class
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    id="modal-close">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-3 md:p-5 space-y-3">
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    You can join the class by entering the class code provided below, or you may join directly if
                    invited by your teacher.
                </p>
            </div>
            <form id="join-class-form" class="p-4 md:p-5" method="POST"
                data-link="{{ route('classes.join', Auth::user()) }}">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="code"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Code</label>
                        <x-input type="text" id="code" name="code" :invalid="$errors->has('code')"
                            placeholder="Enter Class Code" required autocomplete="on" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Join class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="leave-class-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button id="leave-modal-close" type="button"
                class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to leave
                    this class?</h3>
                <form id="leave-class-form" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                <button form="leave-class-form" type="submit"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, I'm sure
                </button>
                <button id="cancel-leave" type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                    cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function joinClass(element) {
            const $targetEl = document.querySelector('#join-class-modal');
            const options = {
                backdrop: 'static',
                closable: false,
                onShow: () => {
                    $('#join-class-form').find('input').val(null);
                }
            };
            const instanceOption = {
                id: 'create-class-modal',
                override: true
            };
            const modal = new Modal($targetEl, options, instanceOption);

            modal.show();

            $('#join-class-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    text: 'Joining class with the code provided. Please wait...',
                    allowOutsideClick: false,
                });
                customSwal.showLoading();
                setTimeout(() => {
                    axios.post($('#join-class-form').data('link'), $('#join-class-form').serialize())
                        .then((response) => {
                            if (response.data.success) {
                                customSwal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: response.data.message,
                                    timer: 5000,
                                    didClose: () => {
                                        modal.hide();
                                        location.reload();
                                    }
                                });
                            } else {
                                customSwal.fire({
                                    title: 'Error',
                                    icon: 'error',
                                    text: response.data.message,
                                    timer: 5000,
                                });
                            }
                        })
                        .catch((error) => {
                            let errMsg = $('<div></div>');

                            $.each(error.response.data.errors, function() {
                                errMsg.append($(`<p>${$(this)[0]}</p>`));
                            });

                            if (error.status === 422) {
                                customSwal.fire({
                                    title: 'Error',
                                    icon: 'error',
                                    html: errMsg,
                                    timer: 5000,
                                });
                            }
                        });
                }, 2000);
            });

            $('#modal-close').on('click', () => {
                modal.hide();
            });
        }

        function leaveClass(element) {
            const $leaveTargetEl = document.querySelector('#leave-class-modal');
            const leaveOptions = {
                backdrop: 'static',
                closable: false,
            };
            const leaveInstanceOption = {
                id: 'leave-class-modal',
                override: true
            };
            const leaveModal = new Modal($leaveTargetEl, leaveOptions, leaveInstanceOption);

            leaveModal.show();

            $('#leave-class-form').data('link', $(element).data('link'));

            $('#leave-class-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    title: 'Leaving classroom. Please wait...',
                    allowOutsideClick: false
                });
                customSwal.showLoading();
                setTimeout(() => {
                    axios.delete($('#leave-class-form').data('link'))
                        .then((response) => {
                            if (response.data.success) {
                                customSwal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: response.data.message,
                                    timer: 5000,
                                    didClose: () => {
                                        leaveModal.hide();
                                        location.reload();
                                    }
                                });
                            } else {
                                customSwal.fire({
                                    title: 'Error',
                                    icon: 'error',
                                    text: response.data.message,
                                    timer: 5000,
                                });
                            }
                        }).catch((error) => {
                            let errMsg = $('<div></div>');

                            $.each(error.response.data.errors, function() {
                                errMsg.append($(`<p>${$(this)[0]}</p>`));
                            });

                            if (error.status === 422) {
                                customSwal.fire({
                                    title: 'Error',
                                    icon: 'error',
                                    html: errMsg,
                                    timer: 5000,
                                });
                            }
                        });
                }, 2000);
            })

            $('#leave-modal-close').on('click', () => {
                leaveModal.hide();
            })
            $('#cancel-leave').on('click', () => {
                leaveModal.hide();
            })
        }
    </script>
@endpush
