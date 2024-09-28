<section class="py-8 antialiased md:py-16">
    @if (Auth::user()->has('enrollments')->count() > 0)
        
    @else
        <div class="mx-auto grid max-w-screen-xl px-4 pb-8 md:grid-cols-12 lg:gap-12 lg:pb-16 xl:gap-0">
            <div class="content-center justify-self-start md:col-span-7 md:text-start">
                <p class=" max-w-2xl text-gray-500 dark:text-gray-400 md:mb-12 md:text-lg mb-3 lg:mb-5 lg:text-xl"
                    id="landing-text">
                    You have not joined any classes yet
                </p>
                <a href="#"
                    class="inline-block rounded-lg bg-primary-700 px-6 py-3.5 text-center font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Join Class
                </a>
            </div>
            <div class="hidden md:col-span-5 md:mt-0 md:flex">
                <img class="dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/girl-shopping-list.svg"
                    alt="shopping illustration" />
                <img class="hidden dark:block"
                    src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/girl-shopping-list-dark.svg"
                    alt="shopping illustration" />
            </div>
        </div>
    @endif
</section>
