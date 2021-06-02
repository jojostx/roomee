@props(['name', 'options', 'selectedOptions'])

<div x-data="{{ $name }}_dropdown()" class="flex flex-col mt-2 md:mt-0 md:col-span-1"
    x-on:keydown.escape.window="show = false">
    <div class="flex flex-col mb-2">
        <label for="{{ $name }}" class="mb-2 font-medium text-gray-700">{{ ucfirst($slot) }}</label>
            <select  x-on:click="openDropdown($refs.{{ $name }}_dropdown)"
            aria-haspopup="true" name="min_price" id="{{ $name }}" class="w-full text-gray-500 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="" disabled selected>Select your {{ ucfirst($slot) }}</option>  
            </select>
    </div>
    <div class="relative">
        <ul style="display: none;" 
            class="absolute left-0 z-20 flex flex-col w-full px-2 py-2 mt-2 overflow-auto overflow-y-auto list-none border rounded-md shadow-sm max-h-56 -top-3 bg-gray-50"
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" 
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95" 
            x-ref="{{ $name }}_dropdown"
            @click.away="show = false" 
            aria-expanded="false">
            @foreach ($options as $option)
            <li tabindex="0" x-on:keydown.arrow-up.prevent="previousUp($event)"
                x-on:keydown.arrow-down.prevent="nextDown($event)"
                x-on:keydown.enter="toggleCheckbox($refs.{{ $option['name'] }}_tag, $refs.{{ $option['name'] }}_checkbox)"
                class="flex flex-row-reverse items-center justify-between mb-1 border border-transparent rounded-md hover:bg-gray-200 focus:border-gray-400 focus:bg-gray-100 focus:outline-none">
                <x-livewire.checkbox type="checkbox" id="{{ $option['name'] }}" wire:model="{{ 'selected'.$slot }}" name="{{ $name }}[]" value="{{$option['id']}}" x-ref="{{ $option['name'] }}_checkbox"
                    :checked="in_array($option['id'], $selectedOptions)?'true':''" class="hidden {{ $name }}_checkboxes"
                    autocomplete="off" />
                <label for="{{ $option['name'] }}"
                    class="flex items-center justify-between w-full h-full px-2 py-2 rounded-md labels_{{ $name }}">
                    {{ ucfirst($option['name']) }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="w-4 icon_check">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
            </li>
            @endforeach
        </ul>
        <div class="relative ">
            @foreach ($options as $option)
            <div x-ref="{{ $option['name'] }}_tag"
                class="{{ $name }}_tags {{ in_array($option['id'], $selectedOptions)?'inline-flex':'hidden' }} items-center mb-2 pl-2 bg-gray-300 rounded-full">
                <p class="pl-1 pointer-events-none">{{ ucfirst($option['name']) }}</p>
                <label tabindex="0"
                    x-on:keydown.enter="uncheck($refs.{{ $option['name'] }}_tag, $refs.{{ $option['name'] }}_checkbox)"
                    for="{{ $option['name'] }}" id="label_{{ $option['name'] }}"
                    wire:click="{{ 'pop_'.$name }}({{$option['id']}})"
                    class="flex items-center px-2 py-2 cursor-pointer {{ $name }}_labels">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
function {{$name}}_dropdown() {
    return {
        show: false,
        uncheck(tag, checkbox) {
            tag.classList.add('hidden')
            checkbox.checked = false;
        },
        toggleCheckbox(tag, checkbox) {
            if (checkbox.checked) {
                checkbox.checked = false;
                tag.classList.add('hidden');
                tag.classList.remove('inline-flex')
                return
            }

            tag.classList.remove('hidden')
            tag.classList.add('inline-flex');
            checkbox.checked = true;
        },
        previousUp(event) {
            (event.target.previousElementSibling) ? event.target.previousElementSibling.focus(): '';
        },
        nextDown(event) {
            (event.target.nextElementSibling) ? event.target.nextElementSibling.focus(): '';
        },
        openDropdown(dropdownId) {
            this.show = true;
            setTimeout(() => {
                dropdownId.firstElementChild.focus()
            }, 50);
        }
    }
}

{
    let labels = document.getElementsByClassName('{{ $name }}_labels');
    let checkboxes = document.getElementsByClassName('{{ $name }}_checkboxes');

    const dropdown = new Dropdown(labels, checkboxes);
    dropdown.addCheckboxListeners();
    dropdown.addLabelListeners();
    dropdown.addDOMListener();
}
</script>
@endpush