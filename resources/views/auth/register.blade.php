<x-guest-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:min-h-screen lg:py-0">
            <a href="/" class="flex items-center mb-6 text-4xl font-semibold">
                <x-application-logo class="w-14 h-14 text-black dark:text-slate-400" />
                <div class="leading-tight tracking-tight font-bold">
                    <span class="text-gray-800 dark:text-gray-300">CODE</span>
                    <span class="text-pink-300">CRAFT</span>
                </div>
            </a>
            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-2xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Create an account
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="{{ route('register') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_type" value="Student">
                        <div class="sm:flex sm:space-x-2">
                            <div class="sm:w-1/2">
                                <x-input-label for="first_name" :invalid="$errors->has('first_name')" :label="__('First Name')" />
                                <x-input type="text" id="first_name" name="first_name" placeholder="First Name"
                                    :invalid="$errors->has('first_name')" required />
                                <x-input-error :messages="$errors->get('first_name')" />
                            </div>
                            <div class="sm:w-1/2">
                                <x-input-label for="last_name" :invalid="$errors->has('last_name')" :label="__('Last Name')" />
                                <x-input type="text" id="last_name" name="last_name" placeholder="Last Name"
                                    :invalid="$errors->has('last_name')" required />
                                <x-input-error :messages="$errors->get('last_name')" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="address" :invalid="$errors->has('address')" :label="__('Address')" />
                            <x-input type="text" id="address" name="address" placeholder="Address"
                                :invalid="$errors->has('address')" />
                            <x-input-error :messages="$errors->get('address')" />
                        </div>
                        <div class="sm:flex sm:space-x-2">
                            <div class="sm:w-3/4">
                                <x-input-label for="email" :invalid="$errors->has('email')" :label="__('Email')" />
                                <x-input type="email" id="email" name="email" placeholder="Email"
                                    :invalid="$errors->has('email')" required />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            <div class="sm:w-5/12">
                                <x-input-label for="contact_number" :invalid="$errors->has('contact_number')" :label="__('Contact Number')" />
                                <x-input type="text" id="contact_number" name="contact_number" placeholder="Contact Number"
                                    :invalid="$errors->has('contact_number')" maxlength="11" 
                                    oninput="numericOnly(this)" />
                                <x-input-error :messages="$errors->get('contact_number')" />
                            </div>
                        </div>
                        <div class="sm:flex sm:space-x-2">
                            <div class="sm:w-1/2">
                                <x-input-label for="password" :invalid="$errors->has('password')" :label="__('Password')" />
                                <x-input type="password" id="password" name="password" placeholder="Password"
                                    :invalid="$errors->has('password')" required />
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                            <div class="sm:w-1/2">
                                <x-input-label for="password_confirmation" :invalid="$errors->has('password_confirmation')" :label="__('Confirm Password')" />
                                <x-input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                                    :invalid="$errors->has('password_confirmation')" required />
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                        </div>
                        <div class="grid items-center gap-3 md:grid-cols-2">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" aria-describedby="terms" type="checkbox"
                                        class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                                        required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept
                                        the
                                        <a class="font-medium text-primary-600 hover:underline dark:text-primary-500"
                                            href="#">Terms and Conditions</a></label>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Create
                                an account</button>
                        </div>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Already have an account? <br>
                            <a href="{{ route('login') }}"
                                class="font-medium text-primary-600 hover:underline dark:text-primary-200">Login
                                here</a>
                            Or
                            <a href="{{ route('be.a.teacher') }}"
                                class="font-medium text-primary-600 hover:underline dark:text-primary-200">Sign up as
                                Teacher</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
