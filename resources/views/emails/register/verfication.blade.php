@component('mail::message')
# Welcome to Project Name

Here you go for some project details if you have any,

@component('mail::button', ['url' => url('/verifyEmail/'.$uuid.'/'.$verificationToken)])
    {{--you can specify colors also for custom theme in above array as color => value--}}
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
