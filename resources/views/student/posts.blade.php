<x-app-layout>
    <main class="py-16 px-6 md:p-16 md:min-h-screen {{ Auth::user()->user_type === 'Admin' ? 'p-4 md:ml-64 h-auto pt-20' : '' }}">
        @if ($communityPosts->count() > 0)
            @foreach ($communityPosts as $post)
                @if (!$post->reports()->where('reporter_id', Auth::user()->id)->where('community_post_id', $post->id)->first())
                    <div
                        class="max-w-4xl mx-auto mb-10 p-6 antialiased bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <!-- Post Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <img src="{{ asset("storage/users-avatar/{$post->author?->avatar}") }}" alt="Teacher Avatar"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div class="ml-3">
                                    <a href="{{ route('profile', $post->author) }}" class="hover:underline">
                                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $post->author?->name }}
                                        </h2>
                                    </a>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">Posted on
                                        {{ $post->created_at?->format('F d, Y - h:i a') }} <br>
                                        @if ($post->created_at->notEqualTo($post->updated_at))
                                            (Edited)
                                        @endif
                                    </p>
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
                                        @canany(['update', 'delete'], $post)
                                            <li>
                                                <button data-link="{{ route('community.post.update', $post) }}"
                                                    data-id="{{ $post->id }}" onclick="editPost(this)"
                                                    class="block w-full text-start px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                                    data-post="{{ $post->title }}" data-content="{{ $post->content }}"
                                                    data-attachments="{{ $post->communityPostAttachments }}">
                                                    Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button data-id="{{ $post->id }}" id="delete-modal-{{ $post->id }}"
                                                    onclick="deletePost(this)"
                                                    data-link="{{ route('community.post.delete', $post) }}"
                                                    class="block w-full text-start px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                    Delete
                                                </button>
                                            </li>
                                        @endcanany
                                        @if ($post->author_id !== Auth::user()->id)
                                            <li>
                                                <button data-id="{{ $post->id }}"
                                                    id="report-modal-{{ $post->id }}" onclick="reportPost(this)"
                                                    data-link="{{ route('community.report', $post) }}"
                                                    class="block w-full text-start px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                    Report
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Post Title -->
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                            {{ $post->title }}
                        </h3>

                        <!-- Post Content -->
                        <p class="text-gray-700 dark:text-gray-200 leading-relaxed mb-6 whitespace-pre-line">{{ $post->content }}</p>

                        @if ($post->communityPostAttachments->count() > 0)
                            <div class="grid @if ($post->communityPostAttachments->count() > 2) grid-rows-2 grid-flow-col @endif gap-2">
                                @foreach ($post->communityPostAttachments as $postAttachment)
                                    <div class="relative @if ($loop->iteration === 3 && $post->communityPostAttachments->count() < 4) row-span-2 @endif">
                                        <img onclick="openPostAttachment(this)" data-link="{{ route('posts.attachments.view', $post) }}" src="{{ asset("storage/{$postAttachment->path}") }}" alt="Group photo 1"
                                            class="rounded-lg object-cover h-full hover:cursor-pointer">
                                    </div>
                                    @if ($loop->iteration === 3 && $post->communityPostAttachments->count() > 4)
                                        <div class="relative">
                                            <img src="{{ asset("storage/{$post->communityPostAttachments[$loop->iteration + 1]?->path}") }}"
                                                alt="Group photo 6" class="rounded-lg object-cover h-full">
                                            <div onclick="openPostAttachment(this)" data-link="{{ route('posts.attachments.view', $post) }}" 
                                                class="hover:cursor-pointer absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white font-bold text-xl">
                                                + {{ $loop->remaining }}
                                            </div>
                                        </div>
                                        @break
                                    @endif
                                @endforeach
                            </div>
                        @endif

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
                                        alt="Student Avatar" class="w-10 h-10 rounded-full mr-3 object-cover">
                                    <div class="w-3/4">
                                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $comment->author->name }}
                                            </h5>
                                            <p id="comment-{{$comment->id}}" class="text-gray-700 dark:text-gray-200 whitespace-pre-line">{{ $comment->content }}</p>
                                        </div>
                                        <div class="flex space-x-3 text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <button class="hover:text-blue-500">Commented</button>
                                            @canany(['update', 'delete'], $comment)
                                                <button id="edit-comment-btn-{{$comment->id}}" type="button"
                                                    onclick="editComment({{ $comment->id }}, {{ $post->id }})"
                                                    class="hover:text-blue-500">
                                                    Edit
                                                </button>
                                                <button id="cancel-edit-comment-btn-{{$comment->id}}" type="button"
                                                    onclick="cancelEditComment({{ $comment->id }}, {{ $post->id }})"
                                                    class="hover:text-blue-500 hidden">
                                                    Cancel
                                                </button>
                                                <button type="button" 
                                                    onclick="deleteComment(this)"
                                                    data-link="{{ route('community.comment.delete', [$post, $comment]) }}"
                                                    class="hover:text-blue-500">
                                                    Delete
                                                </button>
                                            @endcanany
                                            <span>•</span>
                                            <span>
                                                {{ $comment->created_at->diffForHumans() }}
                                                @if ($comment->created_at->notEqualTo($comment->updated_at))
                                                    (Edited)
                                                @endif
                                            </span>
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
                                    data-link="{{ route('community.comment', $post) }}">
                                    @csrf
                                    <input type="hidden" name="comment">
                                    <x-textarea name="content" placeholder="Post a comment..." rows="3" />
                                    <button id="comment-btn-{{$post->id}}" type="button" onclick="postComment({{ $post->id }})"
                                        class="mt-3 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 px-4 py-2 rounded-md">
                                        Post Comment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="image-preview" class="flex flex-wrap mt-4">
                        @foreach ($post->communityPostAttachments as $upload)
                            <input type="hidden" class="uploaded-file-{{ $upload->community_post_id }}"
                                data-name="{{ $upload->original_name }}" value="{{ asset("storage/{$upload->path}") }}">
                        @endforeach
                    </div>
                @endif
            @endforeach
        @else
            <section class="bg-white dark:bg-gray-900">
                <div class="py-8 px-4 mx-auto max-w-screen-md text-center lg:py-16 lg:px-12">
                    <svg class="mx-auto mb-4 w-12 h-12 text-gray-700 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m8 8-4 4 4 4m8 0 4-4-4-4m-2-3-4 14" />
                    </svg>
                    <h1
                        class="mb-4 text-4xl font-bold tracking-tight leading-none text-gray-900 lg:mb-6 dark:text-white">
                        No Community Posts Yet
                    </h1>
                </div>
            </section>
        @endif
    </main>

<div id="edit-post-modal" tabindex="-1" aria-hidden="true"
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
                    id="edit-post-close">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="edit-post-form" class="p-4 md:p-5" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="name" value="{{ $fileName }}">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="title"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <x-input type="text" id="post_title" name="title" :invalid="$errors->has('title')"
                            placeholder="Enter Post Title" required autocomplete="on" />
                    </div>
                    <div class="col-span-2">
                        <label for="content"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Content
                        </label>
                        <x-textarea rows="4" id="post_content" name="content" :invalid="$errors->has('content')"
                            placeholder="Write something..." required />
                    </div>
                </div>
            </form>

            <div class="flex items-center justify-center p-4 md:p-5">
                <form action="{{ route('community.temp.img') }}"
                    class="dropzone w-full border-2 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                    id="editDropzone" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="name" value="{{ $fileName }}">
                    <div class="dz-message text-center text-gray-600 dark:text-gray-200">
                        <h2 class="text-lg font-semibold">Add Photos</h2>
                        <p class="mt-2 text-sm">Drop or click here to upload.</p>
                    </div>
                </form>
            </div>


            <div class="flex justify-end p-4 md:p-5">
                <button type="submit" form="edit-post-form"
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

<div id="delete-post-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button id="delete-post-close" type="button"
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


<div id="delete-comment-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button id="delete-comment-close" type="button"
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
                    this comment?</h3>
                <form id="delete-comment-form" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                <button form="delete-comment-form" type="submit"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, I'm sure
                </button>
                <button id="cancel-comment-delete" type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                    cancel</button>
            </div>
        </div>
    </div>
</div>


<!-- Large Modal -->
<div id="view-post-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-7xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    View Post
                </h3>
                <button id="view-post-close" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <div id="loader" class="flex justify-center">
                    <div role="status">
                        <svg aria-hidden="true"
                            class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div id="post-image-content" class="hidden">

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function editPost(element) {
            let editDropzone;
            new Dropzone('#editDropzone', {
                paramName: "file", // Use file[] for multiple file uploads
                maxFilesize: 30, // MB
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                removedfile: function(file) {
                    let fileName = file.name;
                    axios({
                        method: 'POST',
                        url: `{{ route('community.temp.delete') }}`,
                        data: {
                            name: fileName,
                            _token: '{{ csrf_token() }}'
                        }
                    }).then((response) => {
                        console.log(response);
                    }).catch((error) => {
                        console.error("Error removing file:", error);
                    });

                    let _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file
                        .previewElement) : void 0;
                },
                init: function() {
                    editDropzone = this;

                    // Loop through hidden inputs to create previews
                    document.querySelectorAll(`.uploaded-file-${$(element).data('id')}`).forEach(function(
                        input) {
                        let mockFile = {
                            name: $(input).data('name')
                        }; // Example file properties
                        editDropzone.emit("addedfile", mockFile);
                        editDropzone.emit("thumbnail", mockFile, input.value); // Set thumbnail
                        editDropzone.emit("complete", mockFile); // Mark the file as complete
                    });

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
                                        editDropzone.destroy();
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

        function postComment(formId) {
            $(`#comment-btn-${formId}`).prop('disabled', true);
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
                            didClose: () => {    
                                $(`#comment-btn-${formId}`).prop('disabled', false);
                            }
                        });
                    }
                }).catch((error) => {
                    $(`#comment-btn-${formId}`).prop('disabled', false);

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

        function editComment(commentId, postId) {
            let comment = $(`#comment-${commentId}`).text();
            $(`#edit-comment-btn-${commentId}`).text('Editing');
            $(`#cancel-edit-comment-btn-${commentId}`).removeClass('hidden');
            $(`#comment-form-${postId}`).find('textarea').val($.trim(comment));
            $(`#comment-form-${postId}`).find('input[name="comment"]').val(commentId);
        }

        function cancelEditComment(commentId, postId) {
            $(`#comment-form-${postId}`).find('textarea').val(null);
            $(`#comment-form-${postId}`).find('input[name="comment"]').val(null);
            $(`#edit-comment-btn-${commentId}`).text('Edit');
            $(`#cancel-edit-comment-btn-${commentId}`).addClass('hidden');
        }

        function deleteComment(element) {
            const $delTargetEl = document.querySelector('#delete-comment-modal');
            const delOptions = {
                backdrop: 'static',
                closable: false,
            };
            const delInstanceOption = {
                id: 'delete-comment-modal',
                override: true
            };
            const deleteModal = new Modal($delTargetEl, delOptions, delInstanceOption);

            deleteModal.show();

            $('#delete-comment-form').data('link', $(element).data('link'));

            $('#delete-comment-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    title: 'Deleting comment. Please wait...',
                    allowOutsideClick: false
                });
                customSwal.showLoading();
                setTimeout(() => {
                    axios.delete($('#delete-comment-form').data('link'))
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

            $('#delete-comment-close').on('click', () => {
                deleteModal.hide();
            });
            $('#cancel-comment-delete').on('click', () => {
                deleteModal.hide();
            });

        }

        function openPostAttachment(image) {
            const $viewTargetEl = document.querySelector('#view-post-modal');
            const viewOptions = {
                backdrop: 'static',
                closable: false,
                onHide: () => {
                    $('#post-image-content').find('img').remove();
                }
            };
            const viewInstanceOption = {
                id: 'view-post-modal',
                override: true
            };
            const viewModal = new Modal($viewTargetEl, viewOptions, viewInstanceOption);

            viewModal.show();

            axios.get($(image).data('link'))
            .then((response) => {
                if (response.status) {
                    if (response.data) {
                        response.data.forEach(element => {
                            let img = $(`<img src="{{ asset('storage/${element}') }}" alt="Post Image" class="rounded-lg object-cover h-full" />`);
                            $('#post-image-content').append(img);
                        });
                        $('#loader').addClass('hidden');
                        $('#post-image-content').removeClass('hidden');
                    }
                }
            })

            $('#view-post-close').on('click', () => {
                viewModal.hide();
                $('#loader').addClass('hidden');
                $('#post-image-content').removeClass('hidden');
            });
        }
    </script>
@endpush
</x-app-layout>
