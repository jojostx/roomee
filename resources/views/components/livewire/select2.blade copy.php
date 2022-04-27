@props(['name', 'options', 'selectedOptions', 'label', 'isRequired'])

<div x-data="select2_{{ $name }}" class="flex flex-col md:mt-0 md:col-span-1" @click.away="show = false" @keydown.escape.window="show = false">
    <div class="flex flex-col mb-2">
        <label for="{{ $name }}_search" class="label">{{ ucfirst($label) }} @if ($isRequired)
            <x-required-field-star /> @endif
        </label>

        <x-input @click="optionsVisible = !optionsVisible" id="{{ $name }}_search" type="text" x-model="search" placeholder="Click to search..." aria-haspopup="true" />
    </div>
    <div class="relative">
        <ul x-show="optionsVisible" @click.away="optionsVisible = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="dropdown_ul" aria-expanded="false">
            <template x-for="(option, index) in filteredOptions()" x-bind:key="option.id">
                <li @click.prevent="toggle(option)" @keydown.arrow-down.prevent="$focus.next()" @keydown.arrow-up.prevent="$focus.previous()" tabindex="0" class="flex flex-row-reverse items-center justify-between mb-1 border border-transparent rounded-md hover:bg-gray-200 focus:border-gray-400 focus:bg-gray-100 focus:outline-none">
                    <input type="checkbox" wire:model="{{ 'selected'.$slot }}"  x-bind:id="_id(option.name)" x-bind:value="option.id" x-bind:checked="isSelected(option)" name="{{ $name }}" class="hidden dropdown_checkboxes" autocomplete="off">

                    <label x-bind:for="_id(option.name)" class="flex items-center justify-between w-full h-full px-2 py-2 rounded-md">
                        <span x-text="option.name"></span>
                        <svg class="hidden w-4" x-bind:class="{ 'hidden': !isSelected(option) }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </label>
                </li>
            </template>
            <template x-if="filteredOptions().length == 0">
                <li class="flex text-gray-600 border border-transparent rounded-md">
                    No matching results
                </li>
            </template>
        </ul>
        <div class="relative">
            <template x-for="(option, index) in selectedOptions()" x-bind:key="option.id">
                <div class="inline-flex items-center pl-2 mx-1 mb-2 bg-gray-300 rounded-md">
                    <span x-text="option.name" class="pl-1 pointer-events-none"></span>
                    <label tabindex="0" x-bind:for="_id(option.name)" @keydown.enter="toggle(option)" class="flex items-center px-2 py-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </label>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('select2_{{ $name }}', () => ({
            filteredOptions() {
                return this.options.filter((option) => {
                    return option.name.includes(this.search.toLowerCase());
                });
            },

            selectedOptions() {
                return this.options.filter((option) => {
                    return this.selected.some((_option) => _option == option.id)
                });
            },

            toggle(item) {
                this.selected = this.isSelected(item) ? this.selected.filter(id => id != item.id) : [...this.selected, item.id]
            },

            isSelected(item) {
                return this.selected.some(_option => _option == item.id)
            },

            _id(t) {
                return t.replace(/\s+/g, '_')
            },

            optionsVisible: false,

            search: "",

            selected: @js($selectedOptions),

            options: @js($options),
        }))
    })
</script>
@endpush