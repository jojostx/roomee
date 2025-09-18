<x-mail::message>
# Verify New Email Address

Please click the button below to verify your new email address.

<x-mail::button :url="$url">
Verify New Email Address
</x-mail::button>

If you did not update your email address, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>