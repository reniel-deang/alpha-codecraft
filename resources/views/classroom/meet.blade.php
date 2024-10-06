<x-app-layout>

    <div id="meet" class="h-screen w-full"></div>

    @push('scripts')
        <script src="https://webapi.codecraftmeet.online/external_api.js"></script>

        <script>
            let joined, left;
            const domain = "webapi.codecraftmeet.online";
            const options = {
                roomName: '{{ $conference->conference_name }}',
                parentNode: document.querySelector('#meet'),
                jwt: "{{ $token }}",
                lang: 'en',
                configOverwrite: {
                    lobby: {
                        autoKnock: true,
                        enableChat: false
                    }
                }
            };
            const api = new JitsiMeetExternalAPI(domain, options);

            api.addEventListener('videoConferenceJoined', () => {
                joined = new Date().toLocaleTimeString('en-PH');
            });

            api.addEventListener('videoConferenceLeft', () => {
                left = new Date().toLocaleTimeString('en-PH');

                axios.post("{{ route('classes.meet.calculate', ['class' => $class, 'conference' => $conference, 'user' => $user]) }}", {
                    time_joined: joined,
                    time_left: left,
                    _token: '{{ csrf_token() }}'
                })
                .then((response) => {
                    setTimeout(() => {
                        window.location.replace("{{ route('classes.view', $class) }}")
                    }, 2000);
                })
                .catch((error) => {
                    console.log(error);
                });
            });
        </script>
    @endpush
</x-app-layout>
