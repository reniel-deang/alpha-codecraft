@extends('classroom.classroom')

@section('posts')
    <!-- Post Button Container -->
    <div class="flex justify-end my-3">
        <button onclick="newPost(this)" data-link="{{ route('classes.post', $class) }}"
            class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            New Post
        </button>
        @if ($class->conference)
            <a href="{{ route('classes.meet.start', [$class, $class->conference]) }}"
                class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="inline-block w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
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

    <!-- Container -->
    @forelse ($class->posts->reverse() as $post)
        <div
            class="max-w-4xl mx-auto mb-10 p-6 antialiased bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <!-- Post Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <img src="{{ asset("storage/users-avatar/{$post->author?->avatar}") }}" alt="Teacher Avatar"
                        class="w-12 h-12 rounded-full object-cover">
                    <div class="ml-3">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            {{ $post->author->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            Posted on {{ $post->created_at->format('F d, Y - h:i a') }} <br>
                            @if ($post->created_at->notEqualTo($post->updated_at))
                                (Edited)
                            @endif
                        </p>
                    </div>
                </div>
                @canany(['update', 'delete'], $post)
                    <div class="ms-auto w-1/4">
                        <button id="dropdown-button-{{ $post->id }}" data-dropdown-toggle="dropdown-{{ $post->id }}"
                            class="inline-block float-right text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5"
                            type="button">
                            <span class="sr-only">Open dropdown</span>
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 16 3">
                                <path
                                    d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdown-{{ $post->id }}"
                            class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2" aria-labelledby="dropdown-button-{{ $post->id }}">
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
                            </ul>
                        </div>
                    </div>
                @endcanany
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
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
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
                        <img src="{{ asset("storage/users-avatar/{$comment->author?->avatar}") }}" alt="Student Avatar"
                            class="w-10 h-10 rounded-full mr-3 object-cover">
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
                    <form id="comment-form-{{ $post->id }}" method="POST"
                        data-link="{{ route('classes.post.comment', [$class, $post]) }}">
                        @csrf
                        <x-textarea name="content" placeholder="Post a comment..." rows="3" />
                        <button type="button" onclick="comment({{ $post->id }})"
                            class="mt-3 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 px-4 py-2 rounded-md">
                            Post Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div
            class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-6 rounded-lg text-center">
            <h2 class="text-gray-700 dark:text-gray-100 text-xl font-semibold mb-2">No posts yet</h2>
            <p class="text-gray-500 dark:text-gray-200 text-sm">
                It looks like there are no posts available. Be the first to create one!
            </p>
        </div>
    @endforelse

@endsection

@section('posts-modal')
    <!-- Create post modal -->
    <div id="post-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        New Post
                    </h3>
                    <button id="post-close" type="button"
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
                <form id="post-form" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="post_title" :label="__('Post Title')" />
                        <x-input id="post_title" name="title" placeholder="Post title" :invalid="$errors->has('title')" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="post_content" :label="__('Post Content')" />
                        <x-textarea id="post_content" name="content" placeholder="Write your content..." rows="5"
                            :invalid="$errors->has('content')" required />
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
                            Save post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="edit-post-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        New Post
                    </h3>
                    <button id="edit-post-close" type="button"
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
                <form id="edit-post-form" class="p-4 md:p-5">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <x-input-label for="post_title" :label="__('Post Title')" />
                        <x-input id="post_title" name="title" placeholder="Post title" :invalid="$errors->has('title')" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="post_content" :label="__('Post Content')" />
                        <x-textarea id="post_content" name="content" placeholder="Write your content..." rows="5"
                            :invalid="$errors->has('content')" required />
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
                            Save post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                        delete
                        this post?</h3>
                    <form id="delete-post-form" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button form="delete-post-form" type="submit"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>
                    <button id="cancel-delete" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                        cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function newPost(element) {
            const $targetEl = document.querySelector('#post-modal');
            const options = {
                backdrop: 'static',
                closable: false,
                onShow: () => {
                    $('#post-form').find('#post_title, #post_content').val(null);
                }
            };
            const instanceOption = {
                id: 'post-modal',
                override: true
            };
            const modal = new Modal($targetEl, options, instanceOption);

            modal.show();
            $('#post-form').data('link', $(element).data('link'));

            $('#post-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    title: 'Posting content. Please wait...',
                    allowOutsideClick: false
                });
                customSwal.showLoading();
                setTimeout(() => {
                    axios.post($('#post-form').data('link'), $('#post-form').serialize())
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

            $('#post-close').on('click', () => {
                modal.hide();
            });
        }

        function editPost(element) {
            const $editTargetEl = document.querySelector('#edit-post-modal');
            const editOptions = {
                backdrop: 'static',
                closable: false,
                onShow: () => {
                    $('#edit-post-form').find('#post_title, #post_content').val(null);
                }
            };
            const editInstanceOption = {
                id: 'edit-post-modal',
                override: true
            };
            const editModal = new Modal($editTargetEl, editOptions, editInstanceOption);

            editModal.show();

            $('#edit-post-form').find('#post_title').val($(element).data('post'));
            $('#edit-post-form').find('#post_content').val($(element).data('content'));
            $('#edit-post-form').data('link', $(element).data('link'));

            $('#edit-post-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    title: 'Updating post. Please wait...',
                    allowOutsideClick: false
                });
                customSwal.showLoading();
                setTimeout(() => {
                    axios.patch($('#edit-post-form').data('link'), $('#edit-post-form').serialize())
                        .then((response) => {
                            if (response.data.success) {
                                customSwal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: response.data.message,
                                    timer: 5000,
                                    didClose: () => {
                                        editModal.hide();
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

            $('#edit-post-close').on('click', () => {
                editModal.hide();
            });
        }

        function deletePost(element) {
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
                    title: 'Deleting post. Please wait...',
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


        function comment(formId) {
            axios.post($(`#comment-form-${formId}`).data('link'),
                    $(`#comment-form-${formId}`).serialize())
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
        }
    </script>
@endpush
