@props(['name', 'options', 'selectedOptions', 'label', 'isRequired'])

<div  x-data="multiselect({ 
        options: {{ json_encode($getOptions()) }}, 
        selectedOptions: {{ json_encode($getSelectedOptions()) }}, 
        })" 
        class="flex flex-col md:mt-0 md:col-span-1" 
        @click.away="closeListbox()" 
        @keydown.escape.window="closeListbox()">
  <div class="flex flex-col mb-2">
    <label for="{{ $name }}_search" class="label">{{ ucfirst($label) }} @if ($isRequired)
      <x-required-field-star /> @endif
    </label>
  </div>
  <div class="relative block w-full transition duration-75 border border-gray-300 divide-y rounded-lg shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600 filament-forms-multi-select-component">
    <div x-on:click.away="closeListbox()" x-on:blur="closeListbox()" x-on:keydown.escape.stop="closeListbox()" class="relative">
      <div x-on:click="openListbox()" aria-haspopup="listbox" tabindex="1" class="relative overflow-hidden rounded-lg">
        <input x-model="search" placeholder="please select your {{ $name }}" type="text" autocomplete="off" @class([ 'block w-full border-0' , 'dark:bg-gray-700 dark:placeholder-gray-400'=> config('forms.dark_mode'),])/>

        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none rtl:right-auto rtl:left-0 rtl:pr-0 rtl:pl-2">
          <svg class="w-5 h-5" x-bind:class="optionsVisible && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="#6B7280" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8l4 4 4-4" />
          </svg>
        </span>
      </div>

      <div x-show="optionsVisible" x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" role="listbox" tabindex="-1" class="absolute z-30 w-full my-1 transition bg-white border border-gray-300 rounded-lg shadow-md focus:outline-none" style="bottom: 40px;">
        <ul class="py-1 overflow-auto text-base leading-6 max-h-60 focus:outline-none">
          <template x-for="(option, index) in filteredOptions()" x-bind:key="option.id">
            <li x-bind:class="{'text-gray-900 ': !isSelected(option)}" @click.prevent="toggle(option)" @keydown.arrow-down.prevent="$focus.next()" @keydown.arrow-up.prevent="$focus.previous()" tabindex="0" role="option" class="relative flex items-center px-1 my-1 text-gray-900 cursor-default select-none">
              <input type="checkbox" wire:model="selected" x-bind:id="_id(option.name)" x-bind:value="option.id" x-bind:checked="isSelected(option)" name="hobbies" class="hidden dropdown_checkboxes" autocomplete="off">

              <label x-bind:for="_id(option.name)" class="flex items-center justify-between w-full h-full px-2 py-2 rounded-md hover:bg-gray-200">
                <span class="block font-normal truncate" x-text="capitalize(option.name)"></span>
                <svg class="flex items-center hidden w-4 text-blue-500" x-bind:class="{ 'hidden': !isSelected(option) }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
              </label>
            </li>
          </template>

          <div x-show="!Object.keys(options).length" class="px-3 py-2 text-sm text-gray-700 cursor-default select-none" style="display: none;">
            <span x-show="filteredOptions().length != 0">
              Start typing to search...
            </span>

            <span x-show="filteredOptions().length == 0" style="display: none;">
              No options match your search.
            </span>
          </div>
        </ul>
      </div>
    </div>
    <div x-show="selectedOptions().length" x-cloak class="relative w-full p-2 overflow-hidden rtl:space-x-reverse">
      <div class="flex flex-wrap gap-1">
        <template class="hidden" x-for="option in selectedOptions()" x-bind:key="option.id">
          <div class="inline-flex items-center justify-center min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight text-blue-700 rounded-xl bg-blue-500/10 space-x-1 rtl:space-x-reverse">
            <span x-text="capitalize(option.name)" class="pl-1 text-left pointer-events-none"></span>
            <label tabindex="0" x-bind:for="_id(option.name)" x-on:click.stop="deselectOption(option)" @keydown.enter="deselectOption(option)" class="w-3 h-3 cursor-pointer shrink-0" role="button">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-full">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </label>
          </div>
        </template>
      </div>
    </div>
  </div>
</div>