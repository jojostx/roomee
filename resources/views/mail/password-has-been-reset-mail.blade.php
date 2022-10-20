<x-mail::message>
# Your password has been changed

Your password was changed at **{{ $user->updated_at }}**

If you did not update your password, reach our support team to provide assistance with
resolving this issue.

<x-mail::button :url="route('contact')">
Contact support  
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
