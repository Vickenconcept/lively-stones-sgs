@component('mail::message')
# Welcome to De Gracious Lively Stones

Hello {{ $user->name }},

Your administrator account has been created. Use the details below to sign in and get started.

- **Email:** {{ $user->email }}
- **Temporary Password:** {{ $plainPassword }}
- **Role:** {{ ucfirst($role) }}

@component('mail::button', ['url' => $loginUrl])
Sign In
@endcomponent

Please change your password after your first login to keep your account secure.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

