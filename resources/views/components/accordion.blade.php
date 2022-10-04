@props(['groups' => null])

@php
    $groups ??= [
            "N/A" => [
                "N/A" => "N/A",
                "N/A" => "N/A",
            ],
        ]
@endphp

@foreach($groups as $group_title => $group)
<div id="group-{{ $loop->iteration }}" class="grid grid-cols-1 md:grid-cols-6">
    <div class="px-2 py-4 border border-b-0 border-secondary-700 md:px-4 md:border-b col-span-full md:col-span-2 md:border-r-0">
        <P class="text-lg font-bold text-primary-700 lg:text-xl">{{ $group_title }}</P>
    </div>
    <div class="border-b border-secondary-700 col-span-full md:col-span-4">
        @foreach($group as $title => $content)
            <div>
                <input id="{{ str($title)->slug()->value() }}" class="hidden accordion-input" type="checkbox" name="faq-accordion">
                <label for="{{ str($title)->slug()->value() }}" class="relative flex items-center justify-between px-4 py-4 border border-b-0 cursor-pointer border-secondary-700 md:px-6 accordion-label hover:bg-primary-300">
                    <p>{{ $title }}</p>
                    <i class="flex-shrink-0 w-6 text-black plus-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </i>
                    <i class="flex-shrink-0 hidden w-6 text-black minus-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M18 12H6" />
                        </svg>
                    </i>
                </label>
                <div class="p-8 text-sm border-l border-r border-secondary-700 accordion-content">{{ $content }}</div>
            </div>
        @endforeach 
    </div>
</div>
@endforeach