<x-app-layout>

    <div id="meet" class="h-screen w-full"></div>

    @push('scripts')
        <script src="https://webapi.codecraftmeet.online/external_api.js"></script>

        <script>
            const domain = "webapi.codecraftmeet.online";
            const options = {
                roomName: "{{ $conference->conference_name }}",
                parentNode: document.querySelector('#meet'),
                userInfo: {
                    email: '{{ $user->email }}',
                    displayName: '{{ $user->name }}',
                },
                lang: 'en'
            };
            const api = new JitsiMeetExternalAPI(domain, options);

            api.addEventListener('videoConferenceLeft', () => {
                window.location.replace("{{ route('classes.view', $class) }}")
            });
        </script>
    @endpush
</x-app-layout>
