@component('mail::message')
# Password Reset Link

Click the button below to reset your password:

@component('mail::button', ['url' => 'http://localhost:3000/login/reset-password/' . $resetLink])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent