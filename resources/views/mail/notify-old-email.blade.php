<x-mail::message>
# Your email address has been changed

Your email address was changed to **{{ $user->email }}** at **{{ $changed_at }}**

If you did not update your email address, reach our support team to provide assistance with
resolving this issue.

<x-mail::button :url="route('contact')">
Contact support  
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>