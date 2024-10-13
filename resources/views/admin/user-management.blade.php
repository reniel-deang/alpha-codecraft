<x-app-layout>
    <main class="p-4 md:ml-64 h-auto w-auto pt-20">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
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
                        <a href="#"
                            class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Users</a>
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
            <table id="users-list"
                class="table-auto md:table-fixed text-nowrap text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700">
                <thead>
                    <tr>
                        <td class="w-fit">Name</td>
                        <td>Email</td>
                        <td>User Type</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('profile', $user) }}" class="hover:underline">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->user_type }}</td>
                            <td>
                                <button onclick="sendWarning(this)" data-link="{{ route('users.send.warning', $user) }}" type="button" class="px-3 py-2 text-xs font-medium text-center rounded-lg focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 me-2 mb-2 dark:focus:ring-yellow-900">
                                    Send Warning
                                </button>
                                <button onclick="banUser(this)" data-link="{{ route('users.ban', $user) }}" type="button" class="px-3 py-2 text-xs font-medium text-center rounded-lg focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    Ban User
                                </button>
                                <button onclick="deleteUser(this)" data-link="{{ route('users.delete', $user) }}" type="button" class="px-3 py-2 text-xs font-medium text-center rounded-lg focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    Delete User
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    @push('scripts')
        <script>
            let usersTable;
            let swalTw;
            document.addEventListener('DOMContentLoaded', function() {
                usersTable = new DataTable('#users-list', {
                    responsive: true,
                    language: {
                        paginate: {
                            first: 'First',
                            previous: 'Prev',
                            next: 'Next',
                            last: 'Last',
                        }
                    }
                })

                swalTw = Swal.mixin({
                    customClass: {
                        popup: 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-200',
                        inputLabel: 'text-wrap px-16'
                    }
                });
            });

            function sendWarning(element) {
                swalTw.fire({
                    title: 'Send Warning',
                    icon: 'question',
                    input: 'textarea',
                    inputLabel: 'Please provide the details regarding why this warning is being issued to the user.',
                    confirmButtonText: 'Send',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: async (message) => {
                        let csrfToken = document.head.querySelector('meta[name="csrf-token"]');
                        try {
                            const url = $(element).data('link');
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': csrfToken.content,
                                },
                                body: JSON.stringify({
                                    message: message
                                })
                            });
                            if (!response.ok) {
                                return Swal.showValidationMessage(`
                                    ${JSON.stringify(await response.json())}
                                `);
                            }
                            return response.json();
                        } catch (error) {
                            Swal.showValidationMessage(`
                                Request failed: ${error}
                            `);
                        }
                    },
                }).then((result) => {
                    if(result.isConfirmed) {
                        if(result.value.success) {
                            swalTw.fire({
                                title: 'Success',
                                icon: 'success',
                                text:  result.value.message,
                                timer: 5000,
                                didClose: () => {
                                    location.reload();
                                }
                            });
                        }
                    }
                })
            }

            function banUser(element) {
                swalTw.fire({
                    title: 'Ban User',
                    icon: 'question',
                    html: `
                        <label for="ban-end" class="swal2-input-label text-wrap mb-3 text-md">Select the date until which the ban should be in effect.</label>
                        <input id="ban-end" min="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="date">
                        <label for="message" class="swal2-input-label text-wrap mb-3 text-md">Please provide the details regarding why this warning is being issued to the user.</label>
                        <textarea id="message" class="swal2-textarea block m-0 mt-3 w-full"></textarea>
                    `,
                    confirmButtonText: 'Send',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        let message = $('#message').val();
                        let date = $('#ban-end').val();
                        let csrfToken = document.head.querySelector('meta[name="csrf-token"]');
                        try {
                            const url = $(element).data('link');
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': csrfToken.content,
                                },
                                body: JSON.stringify({
                                    ban_effective: date,
                                    message: message
                                })
                            });
                            if (!response.ok) {
                                return Swal.showValidationMessage(`
                                    ${JSON.stringify(await response.json())}
                                `);
                            }
                            return response.json();
                        } catch (error) {
                            Swal.showValidationMessage(`
                                Request failed: ${error}
                            `);
                        }
                    },
                }).then((result) => {
                    if(result.isConfirmed) {
                        if(result.value.success) {
                            swalTw.fire({
                                title: 'Success',
                                icon: 'success',
                                text:  result.value.message,
                                timer: 5000,
                                didClose: () => {
                                    location.reload();
                                }
                            });
                        }
                    }
                })
            }

            function deleteUser(element) {
                swalTw.fire({
                    title: 'Delete User',
                    icon: 'question',
                    input: 'textarea',
                    inputLabel: 'Please provide the details regarding why this user account will be deleted.',
                    confirmButtonText: 'Send',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: async (message) => {
                        let csrfToken = document.head.querySelector('meta[name="csrf-token"]');
                        try {
                            const url = $(element).data('link');
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': csrfToken.content,
                                },
                                body: JSON.stringify({
                                    message: message
                                })
                            });
                            if (!response.ok) {
                                return Swal.showValidationMessage(`
                                    ${JSON.stringify(await response.json())}
                                `);
                            }
                            return response.json();
                        } catch (error) {
                            Swal.showValidationMessage(`
                                Request failed: ${error}
                            `);
                        }
                    },
                }).then((result) => {
                    if(result.isConfirmed) {
                        if(result.value.success) {
                            swalTw.fire({
                                title: 'Success',
                                icon: 'success',
                                text:  result.value.message,
                                timer: 5000,
                                didClose: () => {
                                    location.reload();
                                }
                            });
                        }
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>
