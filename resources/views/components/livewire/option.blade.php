@props(['selected' => false])

<option {{ $selected ? 'selected = selected' : '' }} {{ $attributes }}>{{ $slot }}</option>