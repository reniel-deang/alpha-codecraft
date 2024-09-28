<x-guest-layout>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="/" class="flex items-center mb-6 text-4xl font-semibold">
                <x-application-logo class="w-14 h-14 text-black dark:text-slate-400" />
                <div class="leading-tight tracking-tight font-bold">
                    <span class="text-gray-800 dark:text-gray-300">CODE</span>
                    <span class="text-pink-300">CRAFT</span>
                </div>
            </a>
            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Sign in to your account
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div>
                            <x-input-label for="email" :invalid="$errors->has('email')" :label="__('Your Email')" />
                            <x-input type="email" name="email" id="email" placeholder="your@email.com"
                                required :invalid="$errors->has('email')" :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                        <div>
                            <x-input-label for="email" :invalid="$errors->has('email')" :label="__('Password')" />
                            <x-input type="password" name="password" id="password" placeholder="password" 
                                required :invalid="$errors->has('email')" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                                        id="remember" name="remember" aria-describedby="remember" type="checkbox">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                                </div>
                            </div>
                            <a href="#"
                                class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot
                                password?</a>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign
                            in</button>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Don’t have an account yet? <br>
                            <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-300">Sign up as Student</a>
                            Or
                            <a href="{{ route('be.a.teacher') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-300">Sign up as Teacher</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

</x-guest-layout>
