@component('mail::message')
# Introduction

<div>
    Hello {{ $name }}, Thanks for joining our service.
</div>
<p>Click the link below toverify your email and to activate your Account</p>

@component('mail::button', ['url' => $link])
Verify your account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
