<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-8">
    
            <!-- Navigation Tabs -->
            <div class="mb-4 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                <ul class="flex flex-wrap justify-center gap-2 -mb-px">
                    <li>
                        <x-class-nav href="{{ route('classes.view', $class) }}" :active="request()->routeIs('classes.view')">
                            Posts
                        </x-class-nav>
                    </li>
                    <li>
                        <x-class-nav href="{{ route('classes.view.lessons', $class) }}" :active="request()->routeIs('classes.view.lessons')">
                            Lessons
                        </x-class-nav>
                    </li>
                    <li>
                        <x-class-nav href="{{ route('classes.view.exams', $class) }}" :active="request()->routeIs('classes.view.exams')">
                            Exams
                        </x-class-nav>
                    </li>
                    <li>
                        <x-class-nav href="{{ route('classes.view.participants', $class) }}" :active="request()->routeIs('classes.view.participants')">
                            Participants
                        </x-class-nav>
                    </li>
                </ul>
            </div>
    
            <!-- Class Card -->
            <div class="relative block max-w-full min-h-48 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <!-- Truncate Title -->
                <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white truncate">
                    {{ $class->name }}
                </h4>
                <!-- Truncate Subject (Single Line) -->
                <span class="block mb-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white overflow-hidden whitespace-nowrap truncate">
                    {{ $class->subject }}
                </span>
                <!-- Truncate Description (Multi-line) -->
                <p class="font-normal text-gray-700 dark:text-gray-400 mt-3 line-clamp-3 overflow-hidden">
                    {{ $class->description }}
                </p>
    
                <!-- Buttons Section -->
                @if (Auth::user()->user_type !== 'Student')
                <div class="absolute bottom-3 right-3 space-y-2 sm:flex sm:space-y-0 sm:space-x-2">
                    <button data-modal-target="show-code" data-modal-toggle="show-code"
                        class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Code
                    </button>
                    <button onclick="inviteStudent(this)" data-link="{{ route('classes.invite', $class) }}"
                        class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Invite
                    </button>
                </div>
                @endif
            </div>
    
            <!-- Content Sections -->
            <div class="mt-8">
                @if (request()->routeIs('classes.view'))
                    @yield('posts')
                @elseif (request()->routeIs('classes.view.lessons'))
                    @yield('lessons')
                @elseif (request()->routeIs('classes.view.exams'))
                    @yield('exams')
                @elseif (request()->routeIs('classes.view.participants'))
                    @yield('participants')
                @endif
            </div>
    
        </div>
    </main>
    




    @if (request()->routeIs('classes.view'))
        @yield('posts-modal')
    @elseif (request()->routeIs('classes.view.lessons'))
        @yield('lessons-modal')
    @elseif (request()->routeIs('classes.view.exams'))
        @yield('exams-modal')
    @elseif (request()->routeIs('classes.view.participants'))
        @yield('participants-modal')
    @endif

    <!-- Create meet modal -->
    <div id="meet-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        New Meeting
                    </h3>
                    <button id="meet-close" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="meet-form" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="conference_name" :label="__('Meet Title')" />
                        <x-input id="conference_name" oninput="noSpaceChar(this)" name="conference_name" placeholder="Meet title" :invalid="$errors->has('conference_name')"
                            required />
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
                            Save meeting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Invite modal -->
    <div id="invite-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Invite Students
                    </h3>
                    <button id="invite-close" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="invite-form" class="p-4 md:p-5" method="POST">
                    @csrf
                    <p id="eror" class="hidden mb-2 text-sm font-medium text-red-700 dark:text-red-500">
                        The email is already in the list and cannot be added again.
                    </p>
                    <p id="require-eror" class="hidden mb-2 text-sm font-medium text-red-700 dark:text-red-500">
                        Please add at least one email address to send the invitation..
                    </p>
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="search"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                                Students</label>

                            <select id="search" class="w-3/4">
                                <option value="" disabled selected></option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->email }}">{{ $student->email }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="addEmailToList()"
                                class="float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:focus:ring-primary-800">
                                Add to list
                            </button>
                        </div>
                    </div>

                    <p class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Students List</p>
                    <input type="hidden" name="emails" id="emails" value="">

                    <ul id="list"
                        class="w-full text-sm font-medium text-gray-900 dark:bg-gray-700 dark:text-white mb-4">

                    </ul>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Invite
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                    <input id="code-text" type="hidden" value="{{ $class->code }}">
                    <button id="class-code"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        <span id="default-message">Copy</span>
                        <span id="success-message" class="hidden">
                            <div class="inline-flex items-center">
                                <svg class="w-3 h-3 text-white me-1.5" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                </svg>
                                Copied!
                            </div>
                        </span>
                    </button>
                    <button data-modal-hide="show-code" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // set the trigger element such as a button or text field
                const $triggerEl = document.getElementById('class-code');

                // set the trigger element such as an input field or code block
                const $targetEl = document.getElementById('code-text');

                // optional options with default values and callback functions
                const options = {
                    contentType: 'input',
                    htmlEntities: false, // infinite
                    onCopy: () => {
                        console.log('code copied successfully!');
                    }
                };

                const instanceOptions = {
                    id: 'class-code',
                    override: true
                };

                const clipboard = new CopyClipboard($triggerEl, $targetEl, options, instanceOptions);

                $($triggerEl).on('click', () => {
                    clipboard.copy();
                });

                const $defaultMessage = $('#default-message');
                const $successMessage = $('#success-message');

                clipboard.updateOnCopyCallback((clipboard) => {
                    $defaultMessage.addClass('hidden');
                    $successMessage.removeClass('hidden');

                    // reset to default state
                    setTimeout(() => {
                        $defaultMessage.removeClass('hidden');
                        $successMessage.addClass('hidden');
                    }, 2000);
                });

            })

            function inviteStudent(element) {
                const $targetEl = document.querySelector('#invite-modal');
                const options = {
                    backdrop: 'static',
                    closable: false,
                    onShow: () => {
                        $('#invite-form').find('#emails').val(null);
                    }
                };
                const instanceOption = {
                    id: 'invite-modal',
                    override: true
                };
                const modal = new Modal($targetEl, options, instanceOption);

                modal.show();
                $('#invite-form').data('link', $(element).data('link'));

                $('#invite-form').on('submit', (event) => {
                    event.preventDefault();
                    if (emails.length < 1) {
                        $('#require-eror').removeClass('hidden');
                        setTimeout(() => {
                            $('#require-eror').addClass('hidden')
                        }, 3000);
                    } else {
                        $('#emails').val(JSON.stringify(emails));
                        customSwal.fire({
                            title: 'Sending invites to students. Please wait...',
                            allowOutsideClick: false
                        });
                        customSwal.showLoading();
                        setTimeout(() => {
                            axios.post($('#invite-form').data('link'), $('#invite-form').serialize())
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
                        }, 1000);
                    }
                })

                $('#invite-close').on('click', () => {
                    modal.hide();
                });

                $('#search').select2({
                    placeholder: 'Search student email'
                });
            }

            let emails = [];

            function addEmailToList() {
                let selectValue = $('#search').val();
                if (selectValue) {
                    if (emails.includes(selectValue)) {
                        $('#eror').removeClass('hidden');
                        setTimeout(() => {
                            $('#eror').addClass('hidden')
                        }, 3000);
                    } else {
                        let listItem = $('<li class="w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600"></li>')
                            .text(selectValue);
                        listItem.append(
                            `<button type="button" data-email="${selectValue}" onclick="remove(this)" class="float-right text-red-500">Remove</button>`
                        )
                        $('#list').append(listItem);
                        emails.push(selectValue);
                    }
                }

            }

            function remove(element) {
                if (emails.includes($(element).data('email'))) {
                    const index = emails.indexOf($(element).data('email'));
                    const x = emails.splice(index, 1);
                    $(element).parent().remove();
                }
            }



            function newMeet(element) {
                const $targetEl = document.querySelector('#meet-modal');
                const options = {
                    backdrop: 'static',
                    closable: false,
                    onShow: () => {
                        $('#meet-form').find('#conference_name').val(null);
                    }
                };
                const instanceOption = {
                    id: 'meet-modal',
                    override: true
                };
                const modal = new Modal($targetEl, options, instanceOption);

                modal.show();
                $('#meet-form').data('link', $(element).data('link'));

                $('#meet-form').on('submit', (event) => {
                    event.preventDefault();
                    customSwal.fire({
                        title: 'Creating meet. Please wait...',
                        allowOutsideClick: false
                    });
                    customSwal.showLoading();
                    setTimeout(() => {
                        axios.post($('#meet-form').data('link'), $('#meet-form').serialize())
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
                    }, 1000);
                })

                $('#meet-close').on('click', () => {
                    modal.hide();
                });
            }
        </script>
    @endpush

</x-app-layout>
