<x-app-layout>
    <main class="p-4 md:ml-64 h-auto w-auto pt-20">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Students</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">List</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table id="students-list" class="table-auto md:table-fixed text-nowrap text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Contact Number</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </main>

    <!-- Create meet modal -->
    <div id="view-details-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        View Details
                    </h3>
                    <button id="view-details-close" type="button"
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
                <div class="p-4 md:p-5">
                    <div id="loader" class="flex justify-center">
                        <div role="status">
                            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="details" class="hidden">
                        <div class="mb-4">
                            <p class="font-bold">Name</p>
                            <p id="name" class="text-base leading-relaxed text-gray-500 dark:text-gray-400"></p>
                        </div>
                        <div class="mb-4">
                            <p class="font-bold">Email</p>
                            <p id="email" class="text-base leading-relaxed text-gray-500 dark:text-gray-400"></p>
                        </div>
                        <div class="mb-4">
                            <p class="font-bold">Contact Number</p>
                            <p id="contact" class="text-base leading-relaxed text-gray-500 dark:text-gray-400"></p>
                        </div>
                        <div class="mb-4">
                            <p class="font-bold">Address</p>
                            <p id="address" class="text-base leading-relaxed text-gray-500 dark:text-gray-400"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let teacherListsTable;
            document.addEventListener('DOMContentLoaded', function() {
                teacherListsTable = new DataTable("#students-list", {
                    ajax: `{{ route('get.students') }}`,
                    responsive: true,
                    order: [],
                    columns: [{
                            data: null,
                            render: (data, type, row) => {
                                return `
                                    <div class="flex space-x-2 items-center">
                                        <svg class="sm:hidden w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M18.425 10.271C19.499 8.967 18.57 7 16.88 7H7.12c-1.69 0-2.618 1.967-1.544 3.271l4.881 5.927a2 2 0 0 0 3.088 0l4.88-5.927Z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>${row.name}</span>    
                                    </div>
                                `
                            }
                        },
                        {
                            data: 'email'
                        },
                        {
                            data: 'contact_number',
                            type: 'text'
                        },
                        {
                            data: null,
                            orderable: false,
                            render: function(data, type, row) {
                                return `
                                    <div class="text-center">
                                        <button type="button" data-link="{{ route('get.student', '') }}/${row.id}" onclick="viewDetail(this)" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                            View Details
                                        </button>
                                    </div>
                                `
                            }
                        }
                    ],
                    searchable: true,
                    sortable: true,
                    lengthChange: false,
                    language: {
                        paginate: {
                            first: 'First',
                            previous: 'Prev',
                            next: 'Next',
                            last: 'Last',
                        }
                    },
                });
                $('.dt-container').addClass('text-gray-900 dark:text-gray-100');

            });

            function viewDetail(element) {
                
                const $targetEl = document.querySelector('#view-details-modal');
                const options = {
                    backdrop: 'static',
                    closable: false,
                    onShow: () => {
                        axios.get($(element).data('link'))
                            .then((response) => {
                                $('#name').text(response.data.name);
                                $('#email').text(response.data.email);
                                $('#contact').text(response.data.contact_number);
                                $('#address').text(response.data.address);

                                $('#loader').addClass('hidden');
                                $('#details').removeClass('hidden');
                            });
                    }
                };
                const instanceOption = {
                    id: 'view-details-modal',
                    override: true
                };
                const modal = new Modal($targetEl, options, instanceOption);

                modal.show();

                $('#view-details-close').on('click', () => {
                    modal.hide();
                    $('#loader').removeClass('hidden');
                    $('#details').addClass('hidden');
                });
            }
        </script>
    @endpush

</x-app-layout>
