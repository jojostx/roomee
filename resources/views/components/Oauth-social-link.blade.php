@php
    $classes = 'flex justify-center items-center border-2 border-gray-300 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-500 hover:bg-blue-50 focus:border-blue-500' 
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} >
    <i> {{ $OAuthLogo }} </i>
    <p> {{ $slot }} </p>
</a>