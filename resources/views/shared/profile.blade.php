<x-app-layout>
    <main class="p-16 {{ Auth::user()->user_type === 'Admin' ? 'p-4 md:ml-64 h-auto pt-20' : '' }}">
        <div
            class="p-8 bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 text-gray-700 dark:text-gray-200 shadow mt-24">
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div
                    class="grid grid-cols-3 text-center order-last md:order-first mt-20 md:mt-0 @if ($user->user_type === 'Admin') invisible @endif">
                    <div>
                        <a href="{{ route('profile.moments', $user) }}">
                            <p class="font-bold text-gray-700 dark:text-gray-200 text-xl">
                                {{ $user->communityPosts()->count() }}
                            </p>
                            <p class="text-gray-400">Moments</p>
                        </a>
                    </div>
                    <div class="@if ($user->user_type === 'Teacher') invisible @endif">
                        <a href="{{ route('profile.certificates', $user) }}">
                            <p class="font-bold text-gray-700 dark:text-gray-200 text-xl">
                                {{ $user->user_type === 'Student' ? $user->certificates()->count() : '' }}
                            </p>
                            <p class="text-gray-400">Certificates</p>
                        </a>
                    </div>
                    <div class="@if ($user->user_type === 'Teacher') invisible @endif">
                        <p class="font-bold text-gray-700 dark:text-gray-200 text-xl">
                            {{ $user->user_type === 'Student' ? Number::format($user->studentDetail?->talktime, precision: 2) : '' }}h
                        </p>
                        <p class="text-gray-400">Talktime</p>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset("storage/users-avatar/{$user->avatar}") }}" alt=""
                        class="w-48 h-48 bg-indigo-100 mx-auto rounded-full shadow-2xl object-cover absolute inset-x-0 top-0 -mt-24 flex items-center justify-center text-indigo-500">
                </div>

                <div
                    class="space-x-8 flex justify-between mt-32 md:mt-0 md:justify-center @if ($user->user_type === 'Admin') invisible @endif">
                    <a href="{{ route('user', $user) }}"
                        class="@if ($user->id === Auth::user()->id) invisible @endif flex items-center text-white py-2 px-4 uppercase rounded bg-blue-400 hover:bg-blue-500 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5">
                        Message
                    </a>
                    <button onclick="editProfile()"
                        class="@if ($user->id !== Auth::user()->id) invisible @endif text-white py-2 px-4 uppercase rounded bg-gray-700 hover:bg-gray-800 shadow hover:shadow-lg font-medium transition transform hover:-translate-y-0.5
                            dark:text-white dark:bg-blue-400 dark:hover:bg-blue-500 ">
                        Edit Profile
                    </button>
                </div>
            </div>

            <div class="mt-20 text-center border-b pb-12">
                <h1 class="text-4xl font-medium text-gray-700 dark:text-gray-200">{{ $user->name }}</h1>
                @php
                    $address = '';
                    $contact = '';
                    $bio = '';
                    if ($user->user_type === 'Student') {
                        $address = $user->studentDetail->address;
                        $contact = $user->studentDetail->contact_number;
                        $bio = $user->studentDetail->bio;
                    } elseif ($user->user_type === 'Teacher') {
                        $address = $user->teacherDetail->address;
                        $contact = $user->teacherDetail->contact_number;
                        $bio = $user->teacherDetail->bio;
                    }
                @endphp

                <div class="flex items-center gap-3 justify-center mt-6">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="font-medium text-xl text-gray-600 dark:text-gray-200 ">
                        {{ $user->user_type === 'Admin' ? 'Admin' : $address }}
                    </p>
                </div>
                <div class="flex items center gap-3 justify-center mt-6">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M5 4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4Zm12 12V5H7v11h10Zm-5 1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="font-light text-md text-gray-600 dark:text-gray-200">
                        {{ $user->user_type === 'Admin' ? 'Admin' : $contact }}</p>
                </div>
            </div>

            <div class="my-6 md:w-3/4 md:mx-auto">
                <h3 class="text-center font-semibold mb-3">Bio</h3>
                <p class="text-gray-700 dark:text-gray-200 text-center font-light lg:px-16 whitespace-pre-line">{{ $user->user_type === 'Admin' ? 'Admin' : $bio }}</p>
            </div>

        </div>

        @if ($user->user_type === 'Teacher')
            <div class="container max-w-4xl mx-auto mt-5 text-gray-700 dark:text-gray-200">
                

                <div class="flex justify-center items-center mb-6">
                    <h1 class="text-2xl font-bold">Schedule</h1>
                    @if ($user->id === Auth::user()->id)
                        <button onclick="setSchedule(this)" data-link="{{ route('profile.set.schedule', $user) }}"
                            class="ml-auto inline-block rounded-lg px-5 py-2.5 bg-primary-700 text-center font-medium text-sm text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Set Schedule
                        </button>
                    @endif
                </div>
                <div class="flex">
                    <div id="calendar"></div>
                    <div id="schedules" data-date="{{ Carbon\Carbon::now()->format('Y-m-d') }}" data-link="{{ route('profile.schedules', $user) }}" class="ml-5 flex justify-center items-center w-full">
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
                        <div id="content" class="hidden">
                            {{-- <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                Available this day {{ Carbon\Carbon::now()->format('F d, Y') }}
                            </h2> --}}
                        </div>
                    </div>
                </div>

                {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4">
                    @if ($user->teacherDetail?->schedules)
                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            @if (in_array($day, $user->teacherDetail?->schedules))
                                <div class="bg-green-500 shadow-lg rounded-lg p-4 flex flex-col">
                                    <div class="text-lg font-semibold text-center mb-2">{{ $day }}</div>
                                    <div class="flex-grow">
                                        <p class="text-center text-gray-200">Available.</p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-500 shadow-lg rounded-lg p-4 flex flex-col">
                                    <div class="text-lg font-semibold text-center mb-2">{{ $day }}</div>
                                    <div class="flex-grow">
                                        <p class="text-center text-gray-200">Not Available.</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <div class="bg-white dark:bg-gray-700 shadow-lg rounded-lg p-4 flex flex-col">
                                <div class="text-lg font-semibold text-center mb-2">{{ $day }}</div>
                                <div class="flex-grow">
                                    <p class="text-center text-gray-500">No set schedule yet.</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div> --}}
            </div>
        @endif

    </main>

    <div id="edit-profile-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit Profile
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        id="edit-modal-close">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="edit-profile-form" class="p-4 md:p-5" method="POST" enctype="multipart/form-data"
                    data-link="{{ route('profile.update', $user) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Profile
                            Picture</label>
                        <div class="flex justify-center items-center mt-2">
                            <img id="profilePicPreview" onclick="$('#profile-pic').trigger('click')"
                                src="{{ $user->avatar ? asset("storage/users-avatar/{$user->avatar}") : asset('storage/users-avatar/avatar.png') }}"
                                class="w-32 h-32 rounded-full border object-cover border-gray-300 mr-4"
                                alt="Profile Picture">
                            <x-input-file id="profile-pic" name="avatar" class="hidden"
                                onchange="previewImage(event)" />
                        </div>
                    </div>
                    <div class="sm:flex sm:space-x-2 mb-4">
                        <div class="sm:w-1/2">
                            <label for="first-name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                            <x-input type="text" id="first-name" name="first_name" :invalid="$errors->has('first_name')"
                                placeholder="First Name" autocomplete="on"
                                value="{{ $user->user_type === 'Teacher' ? $user->teacherDetail?->first_name : $user->studentDetail?->first_name }}" />
                        </div>
                        <div class="sm:w-1/2">
                            <label for="last-name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                            <x-input type="text" id="last-name" name="last_name" :invalid="$errors->has('last_name')"
                                placeholder="Last Name" autocomplete="on"
                                value="{{ $user->user_type === 'Teacher' ? $user->teacherDetail?->last_name : $user->studentDetail?->last_name }}" />
                        </div>
                    </div>
                    <div class="flex space-x-3 mb-4">
                        <div class="sm:w-1/2">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <x-input type="text" id="email" name="email" :invalid="$errors->has('email')"
                                placeholder="Email" autocomplete="on" value="{{ $user->email }}" />
                        </div>
                        <div class="sm:w-1/2">
                            <label for="contact"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact</label>
                            <x-input type="text" id="contact" name="contact_number" :invalid="$errors->has('contact_number')"
                                placeholder="Contact Number" autocomplete="on"
                                value="{{ $user->user_type === 'Teacher' ? $user->teacherDetail?->contact_number : $user->studentDetail?->contact_number }}" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="address"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <x-input type="text" id="address" name="address" :invalid="$errors->has('address')"
                            placeholder="Address" autocomplete="on"
                            value="{{ $user->user_type === 'Teacher' ? $user->teacherDetail?->address : $user->studentDetail?->address }}" />
                    </div>
                    <div class="mb-4">
                        <label for="bio"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bio</label>
                        <x-textarea id="bio" name="bio" rows="4" :invalid="$errors->has('bio')"
                            placeholder="Write something about you..." :value="$user->user_type === 'Teacher' ? $user->teacherDetail?->bio : $user->studentDetail?->bio" />
                    </div>
                </form>

                <div class="flex justify-end p-4 md:p-5">
                    <button type="submit" form="edit-profile-form"
                        class="w-full text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="schedule-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Set Schedule
                    </h3>
                    <button id="schedule-close" type="button"
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
                <form id="schedule-form" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                        <x-input type="date" id="date" name="date" :invalid="$errors->has('date')" :value="Carbon\Carbon::now()->format('Y-m-d')" />
                    </div>
                    <div class="mb-4">
                        <label for="start_time"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Time</label>
                        <x-input type="time" id="start_time" name="start_time" :invalid="$errors->has('start_time')" />
                    </div>
                    <div class="mb-4">
                        <label for="end_time"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Time</label>
                        <x-input type="time" id="end_time" name="end_time" :invalid="$errors->has('end_time')" />
                    </div>

                    {{-- <div class="mb-4">
                        <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Set your schedule base on the day you are available.</span>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="monday" type="checkbox" name="schedules[]" value="Monday" @checked($user->user_type === 'Teacher' && in_array('Monday', $user->teacherDetail?->schedules))
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="monday" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Monday</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="tuesday" type="checkbox" name="schedules[]" value="Tuesday" @checked($user->user_type === 'Teacher' && in_array('Tuesday', $user->teacherDetail?->schedules)) 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="tuesday" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tuesday</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="wednesday" type="checkbox" name="schedules[]" value="Wednesday" @checked($user->user_type === 'Teacher' && in_array('Wednesday', $user->teacherDetail?->schedules)) 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="wednesday" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Wednesday</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="thursday" type="checkbox" name="schedules[]" value="Thursday" @checked($user->user_type === 'Teacher' && in_array('Thursday', $user->teacherDetail?->schedules)) 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="thursday" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Thursday</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="friday" type="checkbox" name="schedules[]" value="Friday" @checked($user->user_type === 'Teacher' && in_array('Friday', $user->teacherDetail?->schedules)) 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="friday" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Friday</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="saturday" type="checkbox" name="schedules[]" value="Saturday" @checked($user->user_type === 'Teacher' && in_array('Saturday', $user->teacherDetail?->schedules)) 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="saturday" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Saturday</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="sunday" type="checkbox" name="schedules[]" value="Sunday" @checked($user->user_type === 'Teacher' && in_array('Sunday', $user->teacherDetail?->schedules)) 
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="sunday" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sunday</label>
                    </div> --}}

                    <div class="flex justify-end">
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Save schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro/build/vanilla-calendar.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro/build/vanilla-calendar.min.js" defer></script>
    @endpush

    @if ($user->user_type === 'Teacher')
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const calendar = new VanillaCalendar('#calendar', {
                        settings: {
                            iso8601: false,
                        },
                        actions: {
                            clickDay(event, self) {
                                $('#content').addClass('hidden')
                                $('#loader').removeClass('hidden');
                                $('#content').children().remove();
                                let selectedDate = self.selectedDates[0];
                                axios.get($('#schedules').data('link'), {
                                    params: {
                                        date: selectedDate
                                    }
                                })
                                .then((response) => {
                                    console.log(response);
                                    if (response.data.length > 0) {
                                        let h2 = `
                                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                                Available schedule/s for the date ${response.data[0].date}
                                            </h2>
                                        `;
                                        $('#schedules').find('#content').append(h2);
                                        response.data.forEach((element, index) => {
                                            let p = `
                                                <p class="text-gray-700 dark:text-gray-200 leading-relaxed">
                                                    ${element.start} - ${element.end}
                                                </p>
                                            `;
                                            $('#schedules').find('#content').append(p);
                                        });
                                        $('#loader').addClass('hidden');
                                        $('#content').removeClass('hidden');
                                    } else {
                                        let html = `
                                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                                No available schedule/s for this date.
                                            </h2>
                                        `;
                                        $('#schedules').find('#content').append(html);
                                        $('#loader').addClass('hidden');
                                        $('#content').removeClass('hidden');
                                    }
                                })
                                .catch((error) => {
                                    console.log(error);
                                })
                            },
                        },
                    });
                    calendar.init();

                    axios.get($('#schedules').data('link'), {
                        params: {
                            date: $('#schedules').data('date')
                        }
                    })
                    .then((response) => {
                        console.log(response)
                        if (response.data.length > 0) {
                            let h2 = `
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                    Available schedule/s for the date ${response.data[0].date}
                                </h2>
                            `;
                            $('#schedules').find('#content').append(h2);
                            response.data.forEach((element, index) => {
                                let p = `
                                    <p class="text-gray-700 dark:text-gray-200 leading-relaxed">
                                        ${element.start} - ${element.end}
                                    </p>
                                `;
                                $('#schedules').find('#content').append(p);
                            });
                            $('#loader').addClass('hidden');
                            $('#content').removeClass('hidden');
                        } else {
                            let html = `
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                                    No available schedule/s for this date.
                                </h2>
                            `;
                            $('#schedules').find('#content').append(html);
                            $('#loader').addClass('hidden');
                            $('#content').removeClass('hidden');
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                });
            </script>
        @endpush
    @endif

    @push('scripts')
        <script>
            // Preview profile picture
            function previewImage(event) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePicPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }

            function editProfile() {
                const $targetEl = document.querySelector('#edit-profile-modal');
                const options = {
                    backdrop: 'static',
                    closable: false
                };
                const instanceOption = {
                    id: 'edit-profile-modal',
                    override: true
                };
                const modal = new Modal($targetEl, options, instanceOption);

                modal.show();

                $('#edit-profile-form').on('submit', (event) => {
                    event.preventDefault();
                    customSwal.fire({
                        text: 'Editing profile. Please wait...',
                        allowOutsideClick: false,
                    });
                    customSwal.showLoading();
                    setTimeout(() => {
                        let form = document.querySelector('#edit-profile-form');
                        let data = new FormData(form);
                        axios.postForm($('#edit-profile-form').data('link'), data)
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

                $('#edit-modal-close').on('click', () => {
                    modal.hide();
                })
            }

            function setSchedule(element) {
                const $targetEl = document.querySelector('#schedule-modal');
                const options = {
                    backdrop: 'static',
                    closable: false
                };
                const instanceOption = {
                    id: 'schedule-modal',
                    override: true
                };
                const modal = new Modal($targetEl, options, instanceOption);

                modal.show();

                $('#schedule-form').data('link', $(element).data('link'));

                $('#schedule-form').on('submit', (event) => {
                    event.preventDefault();
                    customSwal.fire({
                        text: 'Updating Schedule. Please wait...',
                        allowOutsideClick: false,
                    });
                    customSwal.showLoading();
                    setTimeout(() => {
                        let form = document.querySelector('#schedule-form');
                        let data = new FormData(form);
                        axios.postForm($('#schedule-form').data('link'), data)
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

                $('#schedule-close').on('click', () => {
                    modal.hide();
                })
            }
        </script>
    @endpush
</x-app-layout>
