<x-app-layout>

    <main class="py-8 antialiased md:py-16 md:max-w-screen-xl md:mx-auto {{ Auth::user()->user_type === 'Admin' ? 'p-4 md:ml-64 h-auto pt-20' : '' }}">
        <div id="certificate"
            class="text-gray-900 max-w-5xl w-full mx-auto certificate border-4 border-gray-400 py-8 px-16 bg-white shadow-lg rounded-lg">

            <!-- Website Name -->
            <div class="text-center">
                <h1 class="text-5xl font-bold">
                    <div class="flex items-center justify-center leading-tight tracking-tight italic">
                        <x-application-logo width="96" />
                        <span class="text-gray-800">CODE</span>
                        <span class="text-pink-300">CRAFT</span>
                    </div>
                </h1>
                <p class="text-md">codecraftcommunity.site</p>
            </div>

            <!-- Certification Title -->
            <div class="mt-10 text-center">
                <h2 class="text-4xl font-bold uppercase">Certificate of Completion</h2>
                <p class="text-lg mt-2">This certifies that</p>
            </div>

            <!-- Student Name -->
            <div class="mt-6 text-center">
                <h3 class="text-3xl font-bold underline">{{ $certificate->student->name }}</h3>
            </div>

            <!-- Course Details -->
            <div class="mt-6 text-center">
                <p class="text-lg">has successfully completed the lesson</p>
                <h4 class="text-2xl font-semibold italic">{{ $certificate->lesson->title }}</h4>
                <p class="text-lg mt-2">on</p>
                <h5 class="text-xl font-semibold">{{ $certificate->created_at->format('F d, Y') }}</h5>
            </div>

            <!-- Additional Details -->
            <div class="mt-10">
                <p class="text-md text-center">
                    This certificate was issued on {{ $certificate->created_at->format('jS \\of F Y') }} by
                    {{ $certificate->lesson->classroom->teacher->name }} and is presented in recognition of
                    the successful completion of the {{ $certificate->lesson->title }}.
                </p>
            </div>

            <!-- Signature Area -->
            <div class="flex justify-between mt-10">
                <!-- Authorized Signature -->
                <div class="text-center">
                    <p class="font-semibold">{{ $certificate->lesson->classroom->teacher->name }} </p>
                    <div class="mt-2 w-32 h-0.5 bg-gray-600 dark:bg-gray-300 mx-auto"></div>
                    <p class="mt-2">Teacher</p>
                </div>

                <!-- Date of Issue -->
                <div class="text-center">
                    <p class="font-semibold">{{ $certificate->created_at->format('F d, Y') }}</p>
                    <div class="mt-2 w-32 h-0.5 bg-gray-600 dark:bg-gray-300 mx-auto"></div>
                    <p class="mt-2">Date Issued</p>
                </div>
            </div>
        </div>
    </main>

    <div class="fixed bottom-5 right-5 flex space-x-4">
        <button onclick="downloadCertificate()" class="bg-green-600 text-white px-4 py-2 rounded-full shadow-lg">
            Download Certificate
        </button>
    </div>

    @push('styles')
        <style>
            /* Tailwind dark mode enabled */
            @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;700&display=swap');

            .certificate {
                font-family: 'Roboto Slab', serif;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

        <script>
            // Download certificate as PDF
            function downloadCertificate() {
                const element = document.getElementById('certificate');
                html2pdf(element, {
                    margin: [0.5, 0, 0.5, 0],
                    filename: 'certificate.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        allowTaint: true,
                        useCors: true
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'letter',
                        orientation: 'landscape'
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
