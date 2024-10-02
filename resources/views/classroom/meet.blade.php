<x-app-layout>

    <div id="meet" class="h-screen w-full"></div>

    @push('scripts')
        <script src="https://api.codecraftmeet.online/external_api.js"></script>

        <script>
            const domain = "api.codecraftmeet.online";
            const options = {
                roomName: "{{ $room }}",
                jwt: "{{ $jwt }}",
                parentNode: document.querySelector('#meet')
            };
            const api = new JitsiMeetExternalAPI(domain, options);

            api.addEventListener('videoConferenceLeft', () => {
                window.location.replace("{{ route('classes.view', $class) }}")
            });
        </script>
    @endpush
</x-app-layout>
