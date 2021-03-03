@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-sm text-red-600">
            {{ __('Oops! Something went wrong.') }}
        </div>

        <ul class="mt-1 list-inside text-xs text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
