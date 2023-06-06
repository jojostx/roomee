<div x-data x-on:click.window="$store.onboarding_steps.show = false;" class="flex flex-col items-center p-4 space-y-6">
    <div class="flex flex-col items-center">
        <h1 class="mb-4 font-semibold text-gray-900">
            {{ $step_title }}
        </h1>
        @if ($step_body)
        <p class="ml-3 text-gray-800">
            {!! $step_body !!}
        </p>
        @endif
    </div>
    <div class="flex-shrink-0 w-full">
        <a href="{{ $step_link ?? '#' }} " class="flex items-center justify-center w-full px-4 py-2 text-sm font-semibold rounded-md cursor-pointer js-cookie-consent-agree cookie-consent__agree text-primary-50 bg-primary-600 hover:bg-primary-800">
            {{ $step_cta }}
        </a>
    </div>
</div>