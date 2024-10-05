<x-app-layout>

    <div id="meet" class="h-screen w-full"></div>

    @push('scripts')
        <script src="https://webapi.codecraftmeet.online/external_api.js"></script>

        <script>
            // const generate = (privateKey, {
            //     id,
            //     name,
            //     email,
            //     avatar,
            //     appId,
            //     kid
            // }) => {
            //     const now = new Date()
            //     const jwt = jsonwebtoken.sign({
            //         aud: 'jitsi',
            //         context: {
            //             user: {
            //                 id,
            //                 name,
            //                 avatar,
            //                 email: email,
            //                 moderator: 'true'
            //             },
            //             features: {
            //                 livestreaming: 'true',
            //                 recording: 'true',
            //                 transcription: 'true',
            //                 "outbound-call": 'true'
            //             }
            //         },
            //         iss: 'chat',
            //         room: '*',
            //         sub: appId,
            //         exp: Math.round(now.setHours(now.getHours() + 3) / 1000),
            //         nbf: (Math.round((new Date).getTime() / 1000) - 10)
            //     }, privateKey, {
            //         algorithm: 'RS256',
            //         header: {
            //             kid
            //         }
            //     })
            //     return jwt;
            // }

            // /**
            //  * Generate a new JWT.
            //  */
            // const token = generate('my private key', {
            //     id: uuid(),
            //     name: "my user name",
            //     email: "my user email",
            //     avatar: "my avatar url",
            //     appId: "my AppID", // Your AppID ( previously tenant )
            //     kid: "my api key"
            // });

            // console.log(token);

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
