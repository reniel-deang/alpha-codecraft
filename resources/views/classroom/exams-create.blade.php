<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-8">
            <div class="relative block max-w-full min-h-48 p-6 bg-white border border-gray-200 rounded-t-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <button id="back-button" onclick="backToLessons(this)" data-link="{{ route('classes.view.lessons', $class) }}"
                    class="absolute right-6 inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Back to lessons
                </button>
                <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $lesson->title }}
                </h4>
                {{-- <span class="mb-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white">
                    {{ $class->subject }}
                </span> --}}
                <p class="font-normal text-gray-700 dark:text-gray-400 mt-3">
                    {{ $lesson->description }}
                </p>
            </div>

            <div class="flex justify-end my-3">
                <button type="button" onclick="submitExam()" class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Save Content
                </button>
                
            </div>

            <form id="create-exam-form" data-link="{{ route('classes.lesson.exam.save', [$class, $lesson]) }}" method="POST">
                @csrf
                <input type="hidden" name="questions">
                <div class="mb-6">
                    <label for="time_limit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Add time limit for the exam ( In minutes )</label>
                    <input type="number" id="time_limit" required name="time_limit" min="1" placeholder="Enter exam time limit (In Minutes)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Questions</h2>
                    <button onclick="addQuestion()" type="button"
                        class="mr-3 mb-3 float-right inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Question
                    </button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
        <script>
            function submitExam() {
                if ($('#create-exam-form').find('textarea').length < 1) {
                    customSwal.fire({
                        text: 'Please add a question first.'
                    })
                } else {
                    customSwal.fire({
                        title: 'Save',
                        icon: 'question',
                        text: 'Save exam content? Once saved, content cannot be edited.',
                        confirmButtonText: 'Save',
                        showCancelButton: true,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            customSwal.fire({
                                title: 'Posting content. Please wait...',
                                allowOutsideClick: false
                            });
                            customSwal.showLoading();
                            setTimeout(() => {
                                let form = document.querySelector('#create-exam-form');
                                let data = new FormData(form);
                                axios.post($('#create-exam-form').data('link'), data)
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

            }

            function addQuestion() {
                let html = `
                    <div class="mb-6">
                        <x-textarea name="questions[]" required placeholder="Write your question..." />
                    </div>
                `;
                $('#create-exam-form').append(html);
            }

            function backToLessons(element) {
                customSwal.fire({
                    title: 'Go Back ?',
                    icon: 'question',
                    text: 'Do you want to go back? Your changes will not be saved.',
                    confirmButtonText: 'Go Back',
                    showCancelButton: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.replace($(element).data('link'));
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
