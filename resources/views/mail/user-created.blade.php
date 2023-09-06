<x-mail::message>
Hi {{ $user->first_name}},

Welcome to Kapi Tasks. Please click the below url to verfy four account.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
