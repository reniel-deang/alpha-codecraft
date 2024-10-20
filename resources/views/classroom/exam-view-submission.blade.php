<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-8">
            <div class="relative block max-w-full min-h-48 p-6 bg-white border border-gray-200 rounded-t-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="absolute right-6">
                    <a href="{{ route('classes.view.exams', $class) }}"
                        class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Back to exams
                    </a>
                </div>
                <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $lesson->title }}
                </h4>
                <p class="font-normal text-gray-700 dark:text-gray-400 mt-3">
                    {{ $lesson->description }}
                </p>
                <p class="absolute left-6 bottom-3 font-normal tracking-tight text-gray-900 dark:text-white">
                    Exam Taker: <span class="ml-3">{{ $user->name }}</span>
                    <br> 
                    Score: <span class="ml-3">{{ $exam?->examScores()->where('student_id', $user->id)->first()?->score }}</span>
                    <br>
                    Remarks: 
                    <span class="ml-3">
                        @if (is_null($exam?->examScores()->where('student_id', $user->id)->first()?->is_pass))
                            No remarks yet.
                        @else
                            @if ( $exam?->examScores()->where('student_id', $user->id)->first()?->is_pass )
                                <span class="text-green-500">Passed.</span>
                            @else
                                <span class="text-red-500">Failed.</span>
                            @endif
                        @endif
                    </span>
                </p>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center my-4">
                    <h2 class="text-2xl font-bold">Exam Questions</h2>
                    @if (Auth::user()->user_type === 'Teacher')
                        @if (is_null($exam->examScores()->where('student_id', $user->id)->first()?->is_pass))
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('classes.lesson.exam.mark.score', [$class, $lesson, $exam, $user, ($exam->examScores()->where('student_id', $user->id)->first() ?? '')]) }}">
                                    @csrf
                                    <input type="hidden" name="remarks" value="pass">
                                    <button class="rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        Pass Student
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('classes.lesson.exam.mark.score', [$class, $lesson, $exam, $user, ($exam->examScores()->where('student_id', $user->id)->first() ?? '')]) }}">
                                    @csrf
                                    <input type="hidden" name="remarks" value="fail">
                                    <button class="rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        Fail Student
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
                @foreach ($exam?->examQuestions as $question)
                    <div class="my-6 bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg">
                        <p class="whitespace-pre-line mb-5">{{ $question->question }}</p>
                        <p class="whitespace-pre-line">{{ $question->answers()->where('student_id', $user->id)->first()->answer }}</p>
                        @if (Auth::user()->user_type === 'Teacher')
                            <div class="flex justify-end gap-2">
                                @if (is_null($question->answers()->where('student_id', $user->id)->first()->is_correct))
                                    <form method="POST" action="{{ route('classes.lesson.exam.mark.answer', [$class, $lesson, $exam, $user, $question->answers()->where('student_id', $user->id)->first()]) }}">
                                        @csrf
                                        <input type="hidden" name="answer" value="incorrect">
                                        <button class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                            Incorrect
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('classes.lesson.exam.mark.answer', [$class, $lesson, $exam, $user, $question->answers()->where('student_id', $user->id)->first()]) }}">
                                        @csrf
                                        <input type="hidden" name="answer" value="correct">
                                        <button class="inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                            Correct
                                        </button>
                                    </form>
                                @else
                                    @if ($question->answers()->where('student_id', $user->id)->first()->is_correct)
                                        <p class="text-sm mb-0 text-green-500">
                                            Correct
                                        </p>
                                    @else
                                        <p class="text-sm mb-0 text-red-500">
                                            Incorrect
                                        </p>
                                    @endif
                                @endif
                            </div>
                        @elseif (Auth::user()->user_type === 'Student')
                        <div class="flex justify-end gap-2">
                            @if (is_null($question->answers()->where('student_id', $user->id)->first()->is_correct))
                                <p class="text-sm mb-0">
                                    Not yet checked
                                </p>
                            @else
                                @if ($question->answers()->where('student_id', $user->id)->first()->is_correct)
                                    <p class="text-sm mb-0 text-green-500">
                                        Correct
                                    </p>
                                @else
                                    <p class="text-sm mb-0 text-red-500">
                                        Incorrect
                                    </p>
                                @endif
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</x-app-layout>