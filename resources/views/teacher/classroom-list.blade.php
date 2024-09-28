<section class="py-8 antialiased md:py-16">
    @if ($classrooms->count() > 0)
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($classrooms as $classroom)
                        <div >
                            <a href="#"class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $classroom->name }}
                                </h5>
                                <h6 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $classroom->subject }}
                                </h6>
                                <p class="font-normal text-gray-700 dark:text-gray-400">
                                    {{ $classroom->description }}
                                </p>
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
                    You have not created any classes yet
                </p>

                <!-- Modal toggle -->
                <button id="modal-toggle"
                    class="block text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    type="button">
                    Create Class
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
</section>

<!-- Main modal -->
<div id="create-class-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Create New Class
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
            <form id="new-class-form" class="p-4 md:p-5" method="POST"
                data-link="{{ route('classes.create', Auth::user()) }}">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <x-input type="text" id="name" name="name" :invalid="$errors->has('name')"
                            placeholder="Enter Class Name" required autocomplete="on" />
                    </div>
                    <div class="col-span-2">
                        <label for="subject"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                        <x-input type="text" id="subject" name="subject" :invalid="$errors->has('subject')"
                            placeholder="Enter Subject" required />
                    </div>
                    <div class="col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                            Description</label>
                        <x-textarea rows="4" id="description" name="description" :invalid="$errors->has('description')"
                            placeholder="Enter Description (optional)" />
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
                        Add new class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const $targetEl = document.querySelector('#create-class-modal');
            const options = {
                backdrop: 'static',
                closable: false,
                onShow: () => {
                    $('#new-class-form').find('input, textarea').val(null);
                }
            };
            const instanceOption = {
                id: 'create-class-modal',
                override: true
            };
            const modal = new Modal($targetEl, options, instanceOption);

            $('#modal-toggle').on('click', () => {
                modal.show();
            });
            $('#modal-close').on('click', () => {
                modal.hide();
            })


            $('#new-class-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    title: 'Creating new classroom. Please wait...',
                    allowOutsideClick: false
                });
                customSwal.showLoading();
                axios.post($('#new-class-form').data('link'), $('#new-class-form').serialize())
                    .then((response) => {
                        console.log(response);
                        if (response.status === 200) {
                            customSwal.fire({
                                title: 'Success',
                                icon: 'success',
                                text: response.data.message,
                                timer: 5000,
                                didClose: () => {
                                    modal.hide();
                                }
                            });
                            location.reload();
                        }
                    });
            })
        });
    </script>
@endpush
