@props(['name', 'options', 'selectedOptions', 'label', 'isRequired'])

<div x-data="{{ $name }}_dropdown()" class="flex flex-col md:mt-0 md:col-span-1" x-on:click.away="show = false" x-on:keydown.escape.window="show = false">
    <div class="flex flex-col mb-2">
        <label for="{{ $name }}" class="label">{{ ucfirst($label) }} @if ($isRequired) <x-required-field-star/> @endif</label>
        <select x-on:click="openDropdown($refs.{{ $name }}_dropdown)" aria-haspopup="true" name="{{$slot}}" id="{{ $name }}" class="select_dropdown">
            <option value="" disabled selected>Select your {{ ucfirst($label) }}</option>
            @if (!$options)
            <option value="">Select your institute of study</option>
            @endif
        </select>
    </div>
    <div class="relative">
        <ul x-cloak class="dropdown_ul" x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" x-ref="{{ $name }}_dropdown" aria-expanded="false">
            @foreach ($options as $option)
                @php
                    $slugOptionName = str($option['name'])->slug('_')->value();
                @endphp
            <li tabindex="0" x-on:keydown.arrow-up.prevent="previousUp($event)" x-on:keydown.arrow-down.prevent="nextDown($event)" x-on:keydown.enter="toggleCheckbox($refs.{{ $slugOptionName }}_tag, $refs.{{ $slugOptionName }}_checkbox)" class="flex flex-row-reverse items-center justify-between mb-1 border border-transparent rounded-md hover:bg-gray-200 focus:border-gray-400 focus:bg-gray-100 focus:outline-none">
                <x-livewire.checkbox type="checkbox" id="{{ $slugOptionName }}" wire:model="{{ 'selected'.$slot }}" name="{{ $name }}[]" value="{{$option['id']}}" x-ref="{{ $slugOptionName }}_checkbox" :checked="in_array($option['id'], $selectedOptions)?'true':''" class="hidden {{ $name }}_checkboxes" autocomplete="off" />
                <label for="{{ $slugOptionName }}" class="flex items-center justify-between w-full h-full px-2 py-2 rounded-md labels_{{ $name }}">
                    {{ ucfirst($option['name']) }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 icon_check">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </label>
            </li>
            @endforeach
        </ul>
        <div class="relative ">
            @foreach ($options as $option)
            @php
                $slugOptionName = str($option['name'])->slug('_')->value();
            @endphp
            <div x-ref="{{ $slugOptionName }}_tag" class="{{ $name }}_tags {{ in_array($option['id'], $selectedOptions)? 'inline-flex' : 'hidden' }} items-center justify-center min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight text-primary-700 rounded-xl bg-primary-500/10 space-x-1 rtl:space-x-reverse">
                <span x-text="'{{ ucfirst($option['name']) }}'" class="pl-1 text-left pointer-events-none"></span>
                <label tabindex="0" x-on:keydown.enter="uncheck($refs.{{ $slugOptionName }}_tag, $refs.{{ $slugOptionName }}_checkbox)" for="{{ $slugOptionName }}" id="label_{{ $slugOptionName }}" class="w-3 h-3 cursor-pointer shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-full">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
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
                if (dropdownId.firstElementChild) {
                    setTimeout(() => {
                        dropdownId.firstElementChild.focus()
                    }, 50);
                }
            }
        }
    }

    document.addEventListener('alpine:init', () => {
        class Dropdown {
            constructor(labels, checkboxes) {
                this.labels = labels;
                this.checkboxes = checkboxes;
            }

            addLabelListeners() {
                for (let label of this.labels) {
                    label.addEventListener('click', (e) => {
                        e.stopPropagation();
                        label.parentElement.classList.add('hidden');
                    })
                }

                return this;
            }

            addCheckboxListeners() {
                for (let dislike of this.checkboxes) {
                    dislike.addEventListener('change', (e) => {
                        e.stopPropagation();
                        if (dislike.checked) {
                            dislike.labels[1].parentElement.classList.add('inline-flex');
                            dislike.labels[1].parentElement.classList.remove('hidden');
                        } else {
                            dislike.labels[1].parentElement.classList.add('hidden');
                            dislike.labels[1].parentElement.classList.remove('inline-flex');
                        }
                    })
                }

                return this;
            }

            addDOMListener() {
                window.addEventListener('DOMContentLoaded', () => {
                    for (let option of this.checkboxes) {
                        if (option.checked) {
                            option.labels[1].parentElement.classList.add('inline-flex');
                            option.labels[1].parentElement.classList.remove('hidden');
                        }
                    }
                })

                return this;
            }
        }

        let labels = document.getElementsByClassName('{{ $name }}_labels');
        let checkboxes = document.getElementsByClassName('{{ $name }}_checkboxes');

        const dropdown = new Dropdown(labels, checkboxes);
        dropdown.addCheckboxListeners();
        dropdown.addLabelListeners();
        dropdown.addDOMListener();
    })
</script>
@endpush