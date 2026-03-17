@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }}  {!! $attributes->merge(['class' => 'rounded-md shadow-xs border-secondary-300 focus:border-primary-300 focus:ring-3 focus:ring-primary-200/50']) !!}>
