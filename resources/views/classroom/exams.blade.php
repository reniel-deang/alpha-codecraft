@extends('classroom.classroom')

@section('exams')
    <div class="flex justify-end my-3">
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
            <p class="font-normal text-gray-700 dark:text-gray-400 my-3">
                {{ $lesson->description }}
            </p>
            @if (Auth::user()->user_type === 'Teacher')
                <p class="absolute left-6 bottom-3 font-normal text-gray-700 dark:text-gray-400">
                    {{ $lesson->exam()->first()->examQuestions()->first()->answers()->count() }} total submissions
                </p>
            @endif
            @if (Auth::user()->user_type === 'Student')
                    @php
                        $progress = $lesson->progress()->where('student_id', Auth::user()->id)->first();
                        if ($progress) {
                            $percentage = ($progress?->completed_sections / $lesson->sections) * 100;
                            $percent = Number::percentage($percentage, maxPrecision: 2);
                        } else {
                            $percent = Number::percentage(0, maxPrecision: 2);
                        }
                    @endphp
                <p class="absolute left-6 bottom-3 font-normal text-gray-700 dark:text-gray-400">
                    @if ($percent === Number::percentage(100, maxPrecision: 2))
                        @if ($lesson->exam()->first()->examScores()->where('student_id', Auth::user()->id)->first())
                            Score: <span class="ml-3">{{ $lesson->exam()->first()->examScores()->where('student_id', Auth::user()->id)->first()->score }}</span>
                            <br>
                            Remarks: 
                            <span class="ml-3">
                                @if ( $lesson->exam()->first()->examScores()->where('student_id', Auth::user()->id)->first()->is_pass )
                                    <span class="text-green-500">Passed.</span>
                                @else
                                    <span class="text-red-500">Failed.</span>
                                @endif
                            </span>
                        @else
                            @if ($percent === Number::percentage(100, maxPrecision: 2))
                                @if (!$lesson->exam()->first()->examQuestions()->first()->answers()->where('student_id', Auth::user()->id)->first())
                                    You have no submission yet.
                                @else
                                    Your submission has not yet been checked by the teacher.
                                @endif
                            @endif
                        @endif
                    @else
                        You have not yet completed this lesson. Complete the lesson first.
                    @endif
                </p>
            @endif

            @if (Auth::user()->user_type === 'Teacher')
                <div class="flex justify-end">
                    @if ($lesson->status === 'published')
                        @if ($lesson->exam()->first())
                            @php
                                $lists = [];
                                foreach ($lesson->exam()->first()->examQuestions()->first()->answers()->get() as $answer) {
                                    $lists[] = [
                                        'id' => $answer?->student?->id,
                                        'name' => $answer?->student?->name,
                                        'avatar' => $answer?->student?->avatar
                                    ];
                                }
                                $submissions = json_encode($lists);
                            @endphp
                            <button onclick="viewSubmissions(this)" data-users="{{ $submissions }}" data-link="{{ route('classes.lesson.exam.view.submission', [$class, $lesson, $lesson->exam()->first(), '']) }}"
                                class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                View Submissions
                            </button>
                        @endif
                    @endif
                </div>
            @endif

            @if (Auth::user()->user_type === 'Student')
                <div class="flex justify-end">
                    @if ($lesson->status === 'published')
                        @if ($percent === Number::percentage(100, maxPrecision: 2))
                            @if (!$lesson->exam()->first()->examQuestions()->first()->answers()->where('student_id', Auth::user()->id)->first())
                                <a href="{{ route('classes.lesson.exam.take', [$class, $lesson, $lesson->exam]) }}"
                                    class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    Take Exam
                                </a>
                            @else
                                <a href="{{ route('classes.lesson.exam.view.submission', [$class, $lesson, $lesson->exam()->first(), Auth::user()]) }}"
                                    class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    View Submission
                                </a>
                            @endif
                        @else
                            <a href="{{ route('classes.lesson.view.section', [$class, $lesson]) }}"
                                class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Go to Lesson
                            </a>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-6 rounded-lg text-center">
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

@section('exams-modal')
    <!-- Invite modal -->
    <div id="submissions-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Student Submissions
                    </h3>
                    <button id="submissions-close" type="button"
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
                    <div id="student-submissions" class="hidden">
    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function viewSubmissions(element) {
            let link = $(element).data('link');
            let students = $(element).data('users');
            const $targetEl = document.querySelector('#submissions-modal');
            const options = {
                backdrop: 'static',
                closable: false,
                onShow: () => {
                    $(students).each((index, element) => {
                        let html = `
                            <div class="bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg flex items-center mb-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <img src="{{ asset("storage/users-avatar") }}/${element.avatar}" alt="Profile Picture" class="w-10 h-10 rounded-full ml-4 object-cover">
                                        <p class="ml-4">${element.name}</p>
                                    </div>
                                </div>
                                <a href="${link}/${element.id}"
                                    class="ml-auto inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    View Submission
                                </a>
                            </div>
                        `;
                        $('#student-submissions').append(html);
                    });
                    $('#loader').addClass('hidden');
                    $('#student-submissions').removeClass('hidden');
                },
                onHide: () => {
                    $('#student-submissions').find('div').remove();
                }
            };
            const instanceOption = {
                id: 'submissions-modal',
                override: true
            };
            const modal = new Modal($targetEl, options, instanceOption);

            modal.show();

            $('#submissions-close').on('click', () => {
                modal.hide();
                $('#loader').removeClass('hidden');
                $('#student-submissions').addClass('hidden');
            })
        }
    </script>
@endpush