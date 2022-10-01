@props(['checked' => false])

<input {{ $checked ? 'checked' : '' }}  {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-secondary-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
