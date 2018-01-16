@component('mail::message')
# Welcome to Project Name

Here you go for some project details if you have any,

@component('mail::button', ['url' => $url])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
