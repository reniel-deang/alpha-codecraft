<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-8">
            <div class="relative block max-w-full min-h-48 p-6 bg-white border border-gray-200 rounded-t-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <button id="back-button" data-link="{{ route('classes.view.exams', $class) }}"
                    class="hidden absolute right-6 rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Back to lessons
                </button>
                <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $lesson->title }}
                </h4>
                <p class="font-normal text-gray-700 dark:text-gray-400 mt-3">
                    {{ $lesson->description }}
                </p>
                <p class="absolute right-8 bottom-8">
                    Remaining Time: <span id="time-limit" class="text-yellow-400">{{ $exam->time_limit }} mins</span>
                </p>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center my-4">
                    <h2 class="text-2xl font-bold">Exam Questions</h2>
                </div>
                <form id="submit-answer-form" data-link="{{ route('classes.lesson.exam.submit.answer', [$class, $lesson, $exam]) }}" method="POST">
                    @csrf
                    @foreach ($exam?->examQuestions as $question)
                        <div class="my-6 bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700 p-4 rounded-lg">
                            <p class="whitespace-pre-line mb-5">{{ $question->question }}</p>
                            <x-textarea name="answers[]" placeholder="Write your answer" />
                        </div>
                    @endforeach

                    <button type="button" onclick="submitExam()" class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="inline-block w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 12H5m14 0-4 4m4-4-4-4" />
                        </svg>
                        Submit Answer
                    </button>
                </form>
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            let interval;
            document.addEventListener('DOMContentLoaded', () => {
                let timeLimit = parseInt({{ $exam->time_limit }});
                interval = setInterval(() => {
                    timeLimit = timeLimit - 1;

                    if (timeLimit === 0) {
                        $('#time-limit').text('Times up!');
                        customSwal.fire({
                            title: 'Submitting answer. Please wait...',
                            allowOutsideClick: false
                        });
                        customSwal.showLoading();
                        setTimeout(() => {
                            let form = document.querySelector('#submit-answer-form');
                            let data = new FormData(form);
                            axios.post($('#submit-answer-form').data('link'), data)
                                .then((response) => {
                                    if (response.data.success) {
                                        customSwal.fire({
                                            title: 'Success',
                                            icon: 'success',
                                            text: response.data.message,
                                            timer: 5000,
                                            didClose: () => {
                                                location.replace($('#back-button').data('link'));
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
                    } else {
                        $('#time-limit').text(`${timeLimit} mins`);
                    }
                    
                    console.log(timeLimit);
                }, 60000);
            });

            function submitExam() {
                customSwal.fire({
                    title: 'Submit Answer',
                    icon: 'question',
                    text: 'Submit Answer? This action cannot be undone.',
                    allowOutsideClick: false,
                    confirmButtonText: 'Submit',
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearInterval(interval);
                        customSwal.fire({
                            title: 'Submitting answer. Please wait...',
                            allowOutsideClick: false
                        });
                        customSwal.showLoading();
                        setTimeout(() => {
                            let form = document.querySelector('#submit-answer-form');
                            let data = new FormData(form);
                            axios.post($('#submit-answer-form').data('link'), data)
                                .then((response) => {
                                    if (response.data.success) {
                                        customSwal.fire({
                                            title: 'Success',
                                            icon: 'success',
                                            text: response.data.message,
                                            timer: 5000,
                                            didClose: () => {
                                                location.replace($('#back-button').data('link'));
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
                })
            }
        </script>
    @endpush
</x-app-layout>