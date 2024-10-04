<x-app-layout>
    <main class="p-16 md:min-h-screen">
        <div class="flex justify-center mb-12">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 flex items-center space-x-4 w-1/2">
                <div class="w-12 h-12 bg-gray-700 rounded-full overflow-hidden">
                    <!-- Replace with the user's profile picture -->
                    <img src="{{ asset('storage/users-avatar') . '/' . Auth::user()->avatar }}" alt="Profile"
                        class="w-full h-full object-cover rounded">
                </div>
                <input type="text" placeholder="Post something..." readonly onclick="openPostModal()"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            </div>
        </div>

        @if ($communityPosts->count() > 0)
            @foreach ($communityPosts as $post)
                <div
                    class="max-w-4xl mx-auto mb-10 p-6 antialiased bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <!-- Post Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <img src="{{ asset("storage/users-avatar/{$post->author?->avatar}") }}" alt="Teacher Avatar"
                                class="w-12 h-12 rounded-full">
                            <div class="ml-3">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $post->author?->name }}</h2>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Posted on
                                    {{ $post->created_at?->format('F d, Y - h:i a') }}</p>
                            </div>
                        </div>
                        <div class="ms-auto w-1/4">
                            <button id="dropdown-button-{{ $post->id }}"
                                data-dropdown-toggle="dropdown-{{ $post->id }}"
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
                            <div id="dropdown-{{ $post->id }}"
                                class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2" aria-labelledby="dropdown-button-{{ $post->id }}">
                                    @canany(['update', 'view', 'delete'], $post)
                                        <li>
                                            <button data-link="{{ route('classes.post.update', [$class, $post]) }}"
                                                data-id="{{ $post->id }}" onclick="editPost(this)"
                                                class="block w-full text-start px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                                data-post="{{ $post->title }}" data-content="{{ $post->content }}">
                                                Edit
                                            </button>
                                        </li>
                                        <li>
                                            <button data-id="{{ $post->id }}" id="delete-modal-{{ $post->id }}"
                                                onclick="deletePost(this)"
                                                data-link="{{ route('classes.post.delete', [$class, $post]) }}"
                                                class="block w-full text-start px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                Delete
                                            </button>
                                        </li>
                                    @endcanany
                                    <li>
                                        <button data-id="{{ $post->id }}" id="report-modal-{{ $post->id }}"
                                            onclick="reportPost(this)"
                                            data-link="{{ route('community.report', $post) }}"
                                            class="block w-full text-start px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                            Report
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Post Title -->
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        {{ $post->title }}
                    </h3>

                    <!-- Post Content -->
                    <p class="text-gray-700 dark:text-gray-200 leading-relaxed mb-6">
                        {{ $post->content }}
                    </p>

                    <!-- Post Actions -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex space-x-4">
                            <button class="text-gray-500 dark:text-gray-300 hover:text-blue-500 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 10h18M3 6h18M3 14h18M3 18h18"></path>
                                </svg>
                                Comment
                            </button>
                        </div>
                        <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $post->comments->count() }}
                            Comments</span>
                    </div>

                    <!-- Comments Section -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Comments</h4>

                        <!-- Single Comment -->
                        @forelse ($post->comments as $comment)
                            <div class="flex items-start mb-4">
                                <img src="{{ asset("storage/users-avatar/{$comment->author?->avatar}") }}"
                                    alt="Student Avatar" class="w-10 h-10 rounded-full mr-3">
                                <div class="w-3/4">
                                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                                        <h5 class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $comment->author->name }}
                                        </h5>
                                        <p class="text-gray-700 dark:text-gray-200">
                                            {{ $comment->content }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-3 text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        <button class="hover:text-blue-500">Commented</button>
                                        <span>•</span>
                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg text-center">
                                <p class="text-gray-500 dark:text-gray-100 text-sm">
                                    No comments yet.
                                </p>
                            </div>
                        @endforelse

                        <!-- Comment Input -->
                        <div class="mt-6">
                            <form id="comment-form" method="POST" data-link="{{ route('community.comment', $post) }}">
                                @csrf
                                <x-textarea name="content" placeholder="Post a comment..." rows="3" />
                                <button
                                    class="mt-3 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 px-4 py-2 rounded-md">
                                    Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </main>

    <div id="create-post-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Create Post
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        id="modal-close">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="new-post-form" class="p-4 md:p-5" method="POST"
                    data-link="{{ route('community.post') }}">
                    @csrf
                    <input type="hidden" name="name" value="{{ $fileName }}">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="title"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                            <x-input type="text" id="title" name="title" :invalid="$errors->has('title')"
                                placeholder="Enter Post Title" required autocomplete="on" />
                        </div>
                        <div class="col-span-2">
                            <label for="content"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Content
                            </label>
                            <x-textarea rows="4" id="content" name="content" :invalid="$errors->has('content')"
                                placeholder="Write something..." required />
                        </div>
                    </div>
                </form>

                <div class="flex items-center justify-center p-4 md:p-5">
                    <form action="{{ route('community.temp.img') }}"
                        class="dropzone w-full border-2 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                        id="myDropzone" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="name" value="{{ $fileName }}">
                        <div class="dz-message text-center text-gray-600 dark:text-gray-200">
                            <h2 class="text-lg font-semibold">Add Photos</h2>
                            <p class="mt-2 text-sm">Drop or click here to upload.</p>
                        </div>
                    </form>
                </div>


                <div class="flex justify-end p-4 md:p-5">
                    <button type="submit" form="new-post-form"
                        class="w-full text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Post
                    </button>
                </div>

            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new Dropzone('#myDropzone', {
                    paramName: "file", // Use file[] for multiple file uploads
                    maxFilesize: 2, // MB
                    acceptedFiles: ".jpeg,.jpg,.png,.gif",
                    addRemoveLinks: true,
                    removedfile: function(file) {
                        // Here you can send an AJAX request to remove the file from the server if needed
                        // For example, if you save the file name in the file object
                        var fileName = file.name; // Or however you track the filename

                        // Example AJAX request to remove the file
                        axios({
                            method: 'POST',
                            url: `{{ route('community.temp.delete') }}`, // Define a route for file deletion
                            data: {
                                name: fileName,
                                _token: '{{ csrf_token() }}'
                            }, // Include CSRF token
                        }).then((response) => {
                            console.log(response);
                        }).catch((error) => {
                            console.error("Error removing file:", error);
                        });

                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file
                            .previewElement) : void 0;
                    },
                    init: function() {
                        this.on("success", function(file, response) {
                            console.log("File uploaded successfully:", response);
                        });
                        this.on("error", function(file, errorMessage) {
                            console.error("Error uploading file:", errorMessage);
                        });
                        this.on("addedfile", function(file) {
                            console.log("File added:", file);
                        });
                    }
                });

                if ($('#comment-form').length > 0) {
                    $('#comment-form').on('submit', (event) => {
                        event.preventDefault();
                        axios.post($('#comment-form').data('link'), $('#comment-form')
                                .serialize())
                            .then((response) => {
                                if (response.data.success) {
                                    location.reload();
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
                    })
                }

            })

            function openPostModal() {
                const $targetEl = document.querySelector('#create-post-modal');
                const options = {
                    backdrop: 'static',
                    closable: false,
                    onShow: () => {
                        $('#new-post-form').find('#name, #subject, #description, textarea').val(null);
                    }
                };
                const instanceOption = {
                    id: 'create-post-modal',
                    override: true
                };
                const modal = new Modal($targetEl, options, instanceOption);

                modal.show();

                $('#new-post-form').on('submit', (event) => {
                    event.preventDefault();
                    customSwal.fire({
                        text: 'Creating post. Please wait...',
                        allowOutsideClick: false,
                    });
                    customSwal.showLoading();
                    setTimeout(() => {
                        axios.post($('#new-post-form').data('link'), $('#new-post-form')
                                .serialize())
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
                    }, 1000);
                });

                $('#modal-close').on('click', () => {
                    modal.hide();
                })
            }
        </script>
    @endpush
</x-app-layout>
