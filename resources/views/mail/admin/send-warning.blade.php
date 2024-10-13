<x-mail::message>
# Dear {{ $data['user'] }},

<p>
    We hope you’re enjoying your experience with {{ config('app.name') }}. 
    We’re reaching out to inform you of a concern regarding your account.
</p>

<p>
    It has come to our attention that {{ $data['message'] }}. 
    We take these matters seriously to ensure a safe and enjoyable environment for all users.
</p>

<p>
    As a result, we must issue a warning regarding this behavior. 
    Please take this opportunity to review our Community Guidelines to avoid any future issues.
</p>

<p>
    If this behavior continues, your account may face temporary suspension or account deletiong. 
    We want to help you maintain a positive experience, so please feel free to reach out if you have any questions or need assistance.
</p>

<p>
    Thank you for your understanding.
</p>



Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
