@component('mail::message')
Your new password is <i>{{ $password }}</i>

@component('mail::button', ['url' => env('APP_URL') . '/auth/signin'])
SignIn
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
