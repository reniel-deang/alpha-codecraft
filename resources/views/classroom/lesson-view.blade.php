<x-app-layout>
    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto">
        <div class="py-8 antialiased md:py-8">
            <div class="relative block max-w-full min-h-48 p-6 bg-white border border-gray-200 rounded-t-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="{{ route('classes.view.lessons', $class) }}"
                    class="absolute right-6 inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Back to lessons
                </a>
                <h4 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $class->name }}
                </h4>
                <span class="mb-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white">
                    {{ $class->subject }}
                </span>
                <p class="font-normal text-gray-700 dark:text-gray-400 mt-3">
                    {{ $class->description }}
                </p>
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
                @endif
            </div>

            <div id="accordion-open" data-accordion="open">
                @foreach ($lesson->sections()->get() as $section)
                    @if ($loop->last)
                        <h2 id="accordion-open-heading-{{ $section->id }}">
                            <button type="button"
                                class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                                data-accordion-target="#accordion-open-body-{{ $section->id }}" aria-expanded="false"
                                aria-controls="accordion-open-body-{{ $section->id }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 me-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $section->title }}
                                </span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-open-body-{{ $section->id }}" class="hidden"
                            aria-labelledby="accordion-open-heading-{{ $section->id }}">
                            <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700 ">
                                <div>
                                    {!! $section->content !!}
                                </div>
                                @if ($user->user_type === 'Student')
                                    @if (!in_array($section->id, $user->progress()->where('lesson_id', $lesson->id)->first()?->completed_sections_id))
                                        <div class="flex justify-end">
                                            <button type="submit" form="mark-as-done-{{$section->id}}"
                                                class="inline-block rounded-lg px-5 py-2.5 mt-3 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                Mark Section as Done
                                            </button>
                                            <form id="mark-as-done-{{$section->id}}" action="{{ route('classes.lesson.section.mark', [$class, $lesson, $section]) }}" method="POST">
                                                @csrf
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @else
                        <h2 id="accordion-open-heading-{{ $section->id }}">
                            <button type="button"
                                class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                                data-accordion-target="#accordion-open-body-{{ $section->id }}" aria-expanded="false"
                                aria-controls="accordion-open-body-{{ $section->id }}">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 me-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $section->title }}
                                </span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-open-body-{{ $section->id }}" class="hidden"
                            aria-labelledby="accordion-open-heading-{{ $section->id }}">
                            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700">
                                <div>
                                    {!! $section->content !!}
                                </div>
                                @if ($user->user_type === 'Student')
                                    @if (!in_array($section->id, $user->progress()->where('lesson_id', $lesson->id)->first()?->completed_sections_id))
                                        <div class="flex justify-end">
                                            <button type="submit" form="mark-as-done-{{$section->id}}"
                                                class="inline-block rounded-lg px-5 py-2.5 mt-3 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                Mark Section as Done
                                            </button>
                                            <form id="mark-as-done-{{$section->id}}" action="{{ route('classes.lesson.section.mark', [$class, $lesson, $section]) }}" method="POST">
                                                @csrf
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </main>

</x-app-layout>
