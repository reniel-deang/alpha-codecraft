@extends('classroom.classroom')

@section('participants')
<!-- Post Button Container -->
<div class="flex justify-end my-3">
    @if ($class->conference)
        <a href="{{ route('classes.meet.start', [$class, $class->conference]) }}"
            class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="inline-block w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            Go to Meeting
        </a>
    @else
        @if (Auth::user()->user_type === 'Teacher')
            <button onclick="newMeet(this)" data-link="{{ route('classes.meet.create', $class) }}"
                class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                New Meeting
            </button>
        @else
            <button type="button"
                class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="inline-block w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
                No Meeting Yet
            </button>
        @endif
    @endif
</div>

<!-- Container for the layout -->
<div class="container mx-auto p-6">

    <!-- Teachers Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Teacher</h2>
        </div>
        <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex items-center">
            <div class="flex items-center">
                <img src="{{ asset("storage/users-avatar/{$class->teacher?->avatar}") }}" alt="Profile Picture" class="w-10 h-10 rounded-full ml-4 object-cover">
                <p class="ml-4">{{ $class->teacher?->name }}</p>
            </div>
        </div>
    </div>

    <!-- Students Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Students</h2>
        </div>
        @if ($enrolled->count() > 0)
            @foreach ($enrolled as $student )
                <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex items-center mb-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <img src="{{ asset("storage/users-avatar/{$student->avatar}") }}" alt="Profile Picture" class="w-10 h-10 rounded-full ml-4 object-cover">
                            <p class="ml-4">{{ $student->name }}</p>
                            
                        </div>
                    </div>
                    @if(Auth::user()->user_type === 'Teacher')
                        <button onclick="kick(this)" data-link="{{ route('classes.kick.student', [$class, $student]) }}"
                            class="ml-auto inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Kick
                        </button>
                    @endif
                </div>
            @endforeach
        @else
            <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex justify-center items-center">
                <p>No students enrolled yet</p>
            </div>
        @endif
    </div>

</div>

@section('participants-modal')
<div id="delete-post-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button id="delete-modal-close" type="button"
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
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                    Are you sure you want to kick this student?
                </h3>
                <form id="delete-post-form" class="hidden" >
                    @csrf
                    @method('DELETE')
                </form>
                <button form="delete-post-form" type="submit"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, I'm sure
                </button>
                <button id="cancel-delete" type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                    cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function kick(element) {
            const $delTargetEl = document.querySelector('#delete-post-modal');
            const delOptions = {
                backdrop: 'static',
                closable: false,
            };
            const delInstanceOption = {
                id: 'delete-post-modal',
                override: true
            };
            const deleteModal = new Modal($delTargetEl, delOptions, delInstanceOption);

            deleteModal.show();

            $('#delete-post-form').data('link', $(element).data('link'));

            $('#delete-post-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    title: 'Student is being kick. Please wait...',
                    allowOutsideClick: false
                });
                customSwal.showLoading();
                setTimeout(() => {
                    axios.delete($('#delete-post-form').data('link'))
                        .then((response) => {
                            if (response.data.success) {
                                customSwal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: response.data.message,
                                    timer: 5000,
                                    didClose: () => {
                                        deleteModal.hide();
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
            });

            $('#delete-post-close').on('click', () => {
                deleteModal.hide();
            });
            $('#cancel-delete').on('click', () => {
                deleteModal.hide();
            });
        }
    </script>
@endpush
    
@endsection