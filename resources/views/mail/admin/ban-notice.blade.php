<x-mail::message>
# Dear {{ $data['user'] }},

<p>
    We hope you’re enjoying your experience with {{ config('app.name') }}.
</p>

<p>
    We regret to inform you that your account with {{ config('app.name') }} has been suspended effective immediately due to {{ $data['message'] }}
</p>

<p>
    The suspension lasts until {{ Carbon\Carbon::parse($data['date_effective'])->format('F d, Y') }}
</p>

<p>
    Thank you for your understanding.
</p>



Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
