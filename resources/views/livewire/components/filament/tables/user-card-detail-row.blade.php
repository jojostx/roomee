@php
$user = $getRecord();
$avatarComponent = Illuminate\Support\Arr::get($getColumns(), 'avatar_path', null)
    ?? Illuminate\Support\Arr::get($getColumns(), 'avatar', null);
$full_name = Illuminate\Support\Arr::get($getColumns(), 'full_name', null)?->getState() ?? 'N/A';
$course_name = Illuminate\Support\Arr::get($getColumns(), 'course.name', null)?->getState() ?? 'N/A';
$min_budget = Illuminate\Support\Arr::get($getColumns(), 'min_budget', null)?->getState() ?? 'N/A';
$max_budget = Illuminate\Support\Arr::get($getColumns(), 'max_budget', null)?->getState() ?? 'N/A';
$similarity_score = Illuminate\Support\Arr::get($getColumns(), 'similarity_score', null)?->getState() ?? 'N/A';
$min_budget_display = is_numeric($min_budget) ? number_format((float) $min_budget) : 'N/A';
$max_budget_display = is_numeric($max_budget) ? number_format((float) $max_budget) : 'N/A';

$townsState = Illuminate\Support\Arr::get($getColumns(), 'towns.name', null)?->getState() ?? [];
$towns = collect(is_array($townsState) ? $townsState : explode(', ', (string) $townsState))->filter();

$pivot_created_at = Illuminate\Support\Arr::get($getColumns(), 'pivot_created_at', null)?->getState() ?? null;
if (filled($pivot_created_at) && !($pivot_created_at instanceof \Carbon\CarbonInterface)) {
    $pivot_created_at = Illuminate\Support\Carbon::parse($pivot_created_at);
}

$is_hard_filter_restricted = (Illuminate\Support\Arr::get($getColumns(), 'hard_filter_restricted', null)?->getState() ?? '0') === '1';
@endphp

<div class="relative">
    <div class="{{ $is_hard_filter_restricted ? 'pointer-events-none select-none opacity-50 blur-[1px]' : '' }}">
        @if (filled($pivot_created_at))
        <div class="pb-2 mb-3 border-b">
            <p class="text-xs text-success-600">
                <span class="inline-flex items-center h-4">
                    @if ($user->recipient_id == auth()->id())
                    <x-heroicon-o-arrow-down class="w-3" /> &nbsp;Received
                    @else
                    <x-heroicon-o-arrow-up class="w-3" /> &nbsp;Sent
                    @endif
                    {{ $pivot_created_at->diffForHumans() }}
                </span>
            </p>
        </div>
        @endif

        <div class="flex justify-between">
            <div class="flex">
                @if ($avatarComponent)
                    {!! $avatarComponent->toHtml() !!}
                @else
                    <div class="w-12 h-12 mr-3 overflow-hidden rounded-full bg-secondary-100">
                        <img
                            src="{{ $user->avatar_path }}"
                            alt="{{ $full_name }} avatar"
                            class="object-cover w-full h-full"
                        >
                    </div>
                @endif
                <div class="max-w-[160px]">
                    <p class="overflow-x-hidden text-base font-semibold text-secondary-700 text-ellipsis">
                        {{ $full_name }}
                    </p>
                    <p title="{{ $course_name }}" class="overflow-x-hidden text-sm text-ellipsis text-secondary-500">
                        {{ $course_name }}
                    </p>
                </div>
            </div>
            <div>
                <x-filament::link href="{{ route('profile.view', ['user' => $user]) }}" size="sm" aria-label="View {{ $full_name }} profile" title="View {{ $full_name }} profile">
                    View profile
                </x-filament::link>
            </div>
        </div>

        <div class="relative flex items-center py-3 border-t">
            <p class="pr-2 text-sm font-semibold text-secondary-500">
                Locations:
            </p>
            <div class="flex items-center overflow-x-scroll md:overflow-hidden">
                <p class="pr-12 capitalize cursor-grabbing md:cursor-default whitespace-nowrap">
                    @foreach ($towns as $town)
                    <span class="inline-flex items-center justify-center space-x-1 text-primary-600 bg-primary-500/10 min-h-5 px-2 py-0.5 text-xs font-semibold rounded-xl whitespace-normal">
                        {{ $town }}
                    </span>
                    @endforeach
                </p>
            </div>
            <div class="absolute right-0 flex items-center justify-end w-12 h-full from-white/50 to-white bg-gradient-to-r">
                <x-heroicon-o-chevron-double-right class="w-4 h-4 opacity-50 md:hidden" />
            </div>
        </div>

        <div class="flex flex-row-reverse items-center border-y">
            <p title="similarity percentage" class="px-4 py-4 ml-auto text-xs font-semibold border-l text-danger-700">{{ $similarity_score }}</p>
            <div class="mr-auto">
                <p class="font-semibold">
                    <span class="text-sm text-secondary-500">Budget:</span>
                    <span class="text-secondary-500">&#8358;</span>{{ $min_budget_display }}
                    -
                    <span class="text-secondary-500">&#8358;</span>{{ $max_budget_display }}
                </p>
            </div>
        </div>
    </div>

    @if ($is_hard_filter_restricted)
    <div class="absolute inset-0 z-20 flex items-center justify-center p-4 text-center bg-white/60 rounded-lg backdrop-blur-xs">
        <p class="text-xs font-semibold text-secondary-700">
            Profile hidden due to your updated hard matching filters.
        </p>
    </div>
    @endif
</div>
