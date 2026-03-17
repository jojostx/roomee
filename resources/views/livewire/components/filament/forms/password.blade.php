@php
$fieldWrapperView = $getFieldWrapperView();
$statePath = $getStatePath();
$isDisabled = $isDisabled();
$prefixActions = method_exists($field, 'getPrefixActions') ? $getPrefixActions() : [];
$suffixActions = method_exists($field, 'getSuffixActions') ? $getSuffixActions() : [];
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    <div
        {{ $attributes->merge($getExtraAttributes())->class(['flex items-center space-x-2 rtl:space-x-reverse group filament-forms-text-input-component']) }}
        x-data="{
            show: false,
            generatePassword: function() {
                let chars = '{{ $getPasswordChars() }}';
                let password = '';
                for (let i = 0; i < {{ $getPasswordLength() }}; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                $wire.set('{{ $statePath }}', password);
                this.show = true;
            }
        }"
    >
        @foreach ($prefixActions as $prefixAction)
            @if (! $prefixAction->isHidden())
                {{ $prefixAction }}
            @endif
        @endforeach

        @if ($icon = $getPrefixIcon())
            <x-dynamic-component :component="$icon" class="w-5 h-5" />
        @endif

        @if ($label = $getPrefixLabel())
            <span class="whitespace-nowrap group-focus-within:text-primary-500 {{ $errors->has($statePath) ? 'text-danger-400' : 'text-gray-400' }}">
                {{ $label }}
            </span>
        @endif

        <div class="relative flex-1">
            <input
                :type="show ? 'text' : 'password'"
                {{ $applyStateBindingModifiers('wire:model') }}="{{ $statePath }}"
                {{ $getExtraAlpineAttributeBag() }}
                {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                {!! $isAutofocused() ? 'autofocus' : null !!}
                {!! $isDisabled ? 'disabled' : null !!}
                id="{{ $getId() }}"
                {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                {!! $isRequired() ? 'required' : null !!}
                {{ $getExtraInputAttributeBag()->class([
                    'block w-full transition duration-75 rounded-lg shadow-xs focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70',
                    'border-gray-300' => ! $errors->has($statePath),
                    'border-danger-600 ring-danger-600' => $errors->has($statePath),
                    '!pr-8' => ! $isCopyable(),
                    '!pr-14' => $isCopyable(),
                ]) }}
            >
            <div class="absolute inset-y-0 right-0 flex items-center gap-1 pr-2 text-sm leading-5">

                @if ($isGeneratable())
                    <button
                        x-tooltip.raw="Generate Random Password"
                        title="Generate Password"
                        type="button"
                        x-on:click.prevent="generatePassword()"
                    >
                        <x-dynamic-component
                            :component="$getGenerateIcon()"
                            class="h-5 text-gray-400 hover:text-gray-500"
                        />
                    </button>
                @endif

                @if ($isCopyable())
                    <button
                        x-tooltip.raw="Copy Password"
                        title="Copy Password"
                        type="button"
                        @click="navigator.clipboard.writeText($el.closest('[x-data]').querySelector('input').value)"
                    >
                        <x-dynamic-component
                            :component="$getCopyIcon()"
                            class="h-5 text-gray-400 hover:text-gray-500"
                        />
                    </button>
                @endif

                @if ($isRevealable())
                <div x-tooltip.raw="Toggle Password Visibility" class="inline-block">
                    <button
                        title="Hide Password"
                        type="button"
                        @click="show = !show"
                        x-bind:class="{ 'block': show, 'hidden': !show }"
                    >
                        <x-dynamic-component
                            :component="$getShowIcon()"
                            class="h-5 text-gray-400 hover:text-gray-500"
                        />
                    </button>

                    <button
                        title="Reveal Password"
                        type="button"
                        @click="show = !show"
                        x-cloak
                        x-bind:class="{ 'hidden': show, 'block': !show }"
                    >
                        <x-dynamic-component
                            :component="$getHideIcon()"
                            class="h-5 text-gray-400 hover:text-gray-500"
                        />
                    </button>
                </div>
                @endif
            </div>
        </div>

        @if ($label = $getSuffixLabel())
            <span class="whitespace-nowrap group-focus-within:text-primary-500 {{ $errors->has($statePath) ? 'text-danger-400' : 'text-gray-400' }}">
                {{ $label }}
            </span>
        @endif

        @if ($icon = $getSuffixIcon())
            <x-dynamic-component :component="$icon" class="w-5 h-5" />
        @endif

        @foreach ($suffixActions as $suffixAction)
            @if (! $suffixAction->isHidden())
                {{ $suffixAction }}
            @endif
        @endforeach
    </div>
</x-dynamic-component>
