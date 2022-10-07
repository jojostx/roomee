@php
  $avatarComponent = Illuminate\Support\Arr::get($getColumns(), 'avatar', null);
  $full_name = Illuminate\Support\Arr::get($getColumns(), 'full_name', null)?->getState() ?? 'N/A';
  $course_name = Illuminate\Support\Arr::get($getColumns(), 'course.name', null)?->getState() ?? 'N/A';
  $towns = Illuminate\Support\Arr::get($getColumns(), 'towns.name', null)?->getState() ?? 'N/A';
  $min_budget = Illuminate\Support\Arr::get($getColumns(), 'min_budget', null)?->getState() ?? 'N/A';
  $max_budget = Illuminate\Support\Arr::get($getColumns(), 'max_budget', null)?->getState() ?? 'N/A';
  $similarity_score = Illuminate\Support\Arr::get($getColumns(), 'similarity_score', null)?->getState() ?? 'N/A';
@endphp

<div class="flex justify-between">
  <div class="flex">
    {{ $avatarComponent->render() }}
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
    <x-filament-support::link href="{{ route('profile.view', ['user' => $getRecord()]) }}" size="sm" aria-label="View {{ $full_name }} profile" title="View {{ $full_name }} profile">
      View profile
    </x-filament-support::link>
  </div>
</div>

<div class="relative flex items-center py-3 border-t">
  <p class="pr-2 text-sm font-semibold text-secondary-500">
    Locations:
  </p>
  <div class="flex items-center overflow-x-scroll md:overflow-hidden">
    <p class="pr-12 text-sm font-semibold capitalize cursor-grabbing md:cursor-default whitespace-nowrap text-secondary-700">
      {{ $towns }}, rumuosi, choba extension, ozioba, aluu
    </p>
  </div>
  <div class="absolute right-0 flex items-center justify-end w-12 h-full from-white/50 to-white bg-gradient-to-r">
    <x-heroicon-o-chevron-double-right class="w-4 h-4 opacity-50 md:hidden"/>
  </div>
</div>

<div class="flex flex-row-reverse items-center border-y">
  <p title="similarity percentage" class="px-4 py-4 ml-auto text-xs font-semibold border-l text-danger-700">{{ $similarity_score }}</p>
  <div class="mr-auto">
    <p class="font-semibold">
      <span class="text-sm text-secondary-500">Budget:</span>
      <span class="text-secondary-500">₦</span>{{ number_format($min_budget) }}
      - 
      <span class="text-secondary-500 ">₦</span>{{ number_format($max_budget) }}
    </p>
  </div>
</div>