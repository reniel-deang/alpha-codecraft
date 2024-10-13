<x-mail::message>
# Dear {{ $data['user'] }},

<p>
    We regret to inform you that your account with {{ config('app.name') }} has been deleted forever 
    due to {{ $data['message'] }}
</p>


Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
