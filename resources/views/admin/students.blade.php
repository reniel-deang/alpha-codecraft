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
                                        <button type="button" onclick="viewDetail(${row.id})" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
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
        </script>
    @endpush

</x-app-layout>
