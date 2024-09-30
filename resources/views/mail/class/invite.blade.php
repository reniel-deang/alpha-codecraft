<x-mail::message>
# Dear {{ $data['name'] }},

You have been invited to join the <span style="font-weight: 700;">{{ $data['class'] }}</span> <br>
Please use the following class code to access: <span style="font-weight: 700;">{{ $data['code'] }}</span>. <br>
If you encounter any issues when joining class, you can click on the button below to join the class directly
or you can contact your instructor. 

<x-mail::button :url="$data['inviteUrl']">
Join Class
</x-mail::button>

Best Regards,<br>
{{ config('app.name') }}
</x-mail::message>
