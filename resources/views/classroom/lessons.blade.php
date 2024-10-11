@extends('classroom.classroom')

@section('lessons')
    <!-- Post Button Container -->
    <div class="flex justify-end my-3">
        @if (Auth::user()->user_type === 'Teacher')
            <button onclick="newLesson(this)" data-link="{{ route('classes.lesson.save', $class) }}"
                class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                New Lesson
            </button>
        @endif
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

    @forelse ($class->lessons->reverse() as $lesson)
        <div class="@if ($lesson->status === 'unpublished' && Auth::user()->user_type === 'Student') hidden @endif mb-5 relative max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ $lesson->title }}
            </h4>
            @if (Auth::user()->user_type === 'Teacher')
                <span
                    class="float-right right-6 absolute top-6 text-sm {{ $lesson->status === 'published' ? 'text-green-500' : 'text-red-500' }}">{{ ucfirst($lesson->status) }}
                    Lesson</span>
            @endif
            <p class="font-normal text-gray-700 dark:text-gray-400 my-3">
                {{ $lesson->description }}
            </p>
            @if (Auth::user()->user_type === 'Teacher')
                <p class="absolute left-6 bottom-3 font-normal text-gray-700 dark:text-gray-400">
                    {{ $lesson->sections()->count() }} sections added / {{ $lesson->sections }} total
                </p>
            @endif

            @if (Auth::user()->user_type === 'Teacher')
                <div class="flex justify-end">
                    @if ($lesson->sections !== $lesson->sections()->count())
                        <button onclick="newSection(this, {{ $lesson->sections }}, {{ $lesson->sections()->count() }})"
                            data-link="{{ route('classes.lesson.add.section', [$class, $lesson]) }}"
                            class="mr-3 inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Section
                        </button>
                    @endif
                    @if ($lesson->sections === $lesson->sections()->count())
                        @if ($lesson->status === 'unpublished')
                            <button onclick="publish({{ $lesson->id }})"
                                class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Publish Lesson
                            </button>
                            <form id="publish-lesson-{{ $lesson->id }}" class="hidden"
                                data-link="{{ route('classes.lesson.publish', [$class, $lesson]) }}" method="POST">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('classes.lesson.view.section', [$class, $lesson]) }}"
                                class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                View Lesson
                            </a>
                        @endif
                    @endif
                </div>
            @endif

            @if (Auth::user()->user_type === 'Student')
                <div class="bg-gray-200 rounded-full dark:bg-gray-700 mt-3">
                    @php
                        $progress = $lesson->progress()->where('student_id', Auth::user()->id)->first();
                        if ($progress) {
                            $percentage = ($progress?->completed_sections / $lesson->sections) * 100;
                            $percentage = "{$percentage}%";
                        }
                    @endphp
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                        style="width: {{ $percentage ?? '0%' }}">
                        {{ $percentage ?? '0%' }}
                    </div>
                </div>

                @if ($lesson->status === 'published')
                    <div class="absolute right-3 top-3">
                        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                            <a href="{{ route('classes.lesson.view.section', [$class, $lesson]) }}"
                                class="inline-flex justify-center items-center rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                @if ($lesson->progress()->where('student_id', Auth::user()->id)->first())
                                    @if ($lesson->certificate()->where('student_id', Auth::user()->id)->first())
                                        View Lesson
                                    @else
                                        Continue
                                    @endif
                                @else 
                                    Start
                                @endif
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    @empty
        <div
            class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-6 rounded-lg text-center">
            <h2 class="text-gray-700 dark:text-gray-100 text-xl font-semibold mb-2">No lessons yet</h2>
            <p class="text-gray-500 dark:text-gray-200 text-sm">
                It looks like there are no lessons available.
                @if (Auth::user()->user_type === 'Teacher')
                    Create lessons for your students now.
                @else
                    Wait for your teacher to add lessons.
                @endif
            </p>
        </div>
    @endforelse

@endsection

@section('lessons-modal')
    <!-- Create post modal -->
    <div id="lesson-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        New Lesson
                    </h3>
                    <button id="lesson-close" type="button"
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
                <form id="lesson-form" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="lesson_title" :label="__('Title')" />
                        <x-input id="lesson_title" name="title" placeholder="Lesson title" :invalid="$errors->has('title')"
                            required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="sections" :label="__('How many sections will you add')" />
                        <x-input type="number" id="sections" name="sections" placeholder="Sections count"
                            :invalid="$errors->has('sections')" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="lesson_description" :label="__('Description')" />
                        <x-textarea id="lesson_description" name="description" placeholder="Description" rows="5"
                            :invalid="$errors->has('description')" required />
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
                            Save lesson
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create post modal -->
    <div id="section-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Add Section
                    </h3>
                    <button id="section-close" type="button"
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
                <form id="section-form" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="section_title" :label="__('Title')" />
                        <x-input id="section_title" name="title" placeholder="Section title" :invalid="$errors->has('title')"
                            required />
                    </div>

                    <div class="mb-4">
                        <div id="section-content" class="h-60"></div>
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
                            Save section
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        /* Light Mode Styles */
        .ql-toolbar.ql-snow {
            background: #f8f8f8;
            /* Light background */
            color: #333;
            /* Dark text */
        }

        .ql-editor.ql-snow {
            background: #fff;
            /* Light background */
            color: #333;
            /* Dark text */
        }

        /* Dark Mode Styles */
        .dark .ql-toolbar.ql-snow {
            background: #333;
            /* Dark background */
            color: #fff;
            /* Light text */
        }

        .dark .ql-editor.ql-snow {
            background: #222;
            /* Dark background */
            color: #fff;
            /* Light text */
        }

        .dark .ql-toolbar button {
            color: #fff;
            /* Light text for buttons */
        }

        .dark .ql-toolbar button:hover {
            background: #444;
            /* Darker on hover */
        }

        .dark .ql-editor.ql-snow .ql-placeholder {
            color: #bbb;
            /* Lighter placeholder color */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const quill = new Quill('#section-content', {
            theme: 'snow'
        });
    </script>
    <script>
        function newLesson(element) {
            const $targetEl = document.querySelector('#lesson-modal');
            const options = {
                backdrop: 'static',
                closable: false,
                onShow: () => {
                    $('#lesson-form').find('#lesson_title, #lesson_content').val(null);
                }
            };
            const instanceOption = {
                id: 'lesson-modal',
                override: true
            };
            const modal = new Modal($targetEl, options, instanceOption);

            modal.show();
            $('#lesson-form').data('link', $(element).data('link'));

            $('#lesson-form').on('submit', (event) => {
                event.preventDefault();
                customSwal.fire({
                    title: 'Posting lesson. Please wait...',
                    allowOutsideClick: false
                });
                customSwal.showLoading();
                setTimeout(() => {
                    axios.post($('#lesson-form').data('link'), $('#lesson-form').serialize())
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

            $('#lesson-close').on('click', () => {
                modal.hide();
            });
        }

        function newSection(element, maxSections, currentSections) {
            const $targetEl = document.querySelector('#section-modal');
            const options = {
                backdrop: 'static',
                closable: false,
                onShow: () => {
                    $('#lesson-form').find('#lesson_title, #lesson_content').val(null);
                }
            };
            const instanceOption = {
                id: 'section-modal',
                override: true
            };
            const modal = new Modal($targetEl, options, instanceOption);

            if (currentSections === maxSections) {
                customSwal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Max section count reached! cannot add anymore.',
                    allowOutsideClick: false,
                    timer: 3000
                });
            } else {
                modal.show();

                $('#section-form').data('link', $(element).data('link'));

                $('#section-form').on('submit', (event) => {
                    event.preventDefault();
                    customSwal.fire({
                        title: 'Adding section. Please wait...',
                        allowOutsideClick: false
                    });
                    customSwal.showLoading();
                    setTimeout(() => {
                        const form = document.querySelector('#section-form');
                        let data = new FormData(form)
                        data.append('content', quill.root.innerHTML);
                        axios.post($('#section-form').data('link'), data)
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
            }



            $('#section-close').on('click', () => {
                modal.hide();
            });
        }

        function publish(lessonId) {
            customSwal.fire({
                title: 'Publish',
                icon: 'question',
                text: 'Publish this lesson ?',
                allowOutsideClick: false,
                confirmButtonText: 'Publish',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    customSwal.fire({
                        title: 'Publishin lesson. Please wait...',
                        allowOutsideClick: false
                    });
                    customSwal.showLoading();
                    setTimeout(() => {
                        axios.post($(`#publish-lesson-${lessonId}`).data('link'),
                                $(`#publish-lesson-${lessonId}`).serialize())
                            .then((response) => {
                                if (response.data.success) {
                                    customSwal.fire({
                                        title: 'Success',
                                        icon: 'success',
                                        text: response.data.message,
                                        timer: 5000,
                                        didClose: () => {
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
            });
        }
    </script>
@endpush
