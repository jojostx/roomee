<x-forms::field-wrapper 
    :id="$getId()" 
    :label="$getLabel()" 
    :label-sr-only="$isAvatar() || $isLabelHidden()" 
    :helper-text="$getHelperText()" 
    :hint="$getHint()" 
    :hint-icon="$getHintIcon()" 
    :required="$isRequired()" 
    :state-path="$getStatePath()">
    <div 
        x-data="customFileUploadFormComponent({
            state: $wire.entangle('{{ $getStatePath() }}'),
            acceptedFileTypes: ['image/jpg', 'image/png', 'image/jpeg'],
            isAvatar: false,
            getAltText,
            imageCropAspectRatio,
            imagePreviewHeight: 320,
            maxSize: 5242880,
            hasImage: false,
            minSize,
            minCroppedWidth: 320,
            maxCroppedWidth: 960,
            minCroppedHeight: 320
        })" 
        {!! ($id=$getId()) ? "id=\" {$id}\"" : null !!} 
        {{ $attributes->merge($getExtraAttributes())->class([
            'filament-forms-file-upload-component',
            'w-32 mx-auto relative' => $isAvatar(),
        ]) }} 
        style="min-height: {{ $isAvatar() ? '8em' : '4.75em') }}" 
        {{ $getExtraAlpineAttributeBag() }} 
        wire:ignore 
        >
        <!-- if the field is avatar then show avatar box else show default -->
        <div class="relative flex flex-col items-center justify-center w-full space-y-1">
            <div @class([
                    'overflow-hidden',
                    'max-w-[24px] max-h-24 mb-4 overflow-hidden bg-gray-200 rounded-full' => isAvatar(),
                    'w-full h-64 mb-2 overflow-hidden rounded-lg shadow-lg' => ! isAvatar()
                ])>
                <img src="{{ $getImage() }}" x-ref="poster" x-bind:alt="getAltText()" width="100%">
            </div>

            <div class="flex items-center justify-center space-x-2">
                <x-filament::button size='sm' x-on:click="$dispatch('open-modal', {id: 'photo-upload-cropper-{{ $getStatePath() }}'})">
                    {{ __('Upload A Photo') }}
                </x-filament::button>
                <x-filament::button size='sm'x-cloak x-show="hasImage" x-on:click="edit(); $dispatch('open-modal', {id: 'photo-upload-cropper-{{ $getStatePath() }}'})">
                    {{ __('Edit') }}
                </x-filament::button>
            </div>
        </div>

        <x-filament::modal width="7xl" id="photo-upload-cropper-{{ $getStatePath() }}" heading="Crop image" x-on:close-modal.window="if($event.detail.id == 'photo-upload-cropper-{{ $getStatePath() }}' ) { resetCropper(); }">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex flex-col items-center justify-center p-6 mt-1 space-y-6">
                    <div x-ref="preview_box" class="flex items-center justify-center w-full overflow-hidden rounded-lg col-span-full">
                        <img x-ref="cropCanvas" x-show="hasCropCanvas" x-cloak src="" alt="crop canvas Image" width="100%">
                        
                        <label for="{{ $getId() }}_input" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer h-96 bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>
                        </label>
                    </div>

                    <input x-on:change="handleFileInputChange()" id="{{ $getId() }}_input" {{ $isDisabled() ? 'disabled' : '' }} {{ $isMultiple() ? 'multiple' : '' }} type="file" {{ $getExtraInputAttributeBag() }} class="sr-only" accept=".jpg, .jpeg, .png" />
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                    <label for="{{ $getId() }}_input" x-cloak x-show="hasImage" tabindex="0">
                        <x-filament::button size='sm' color='secondary'>
                            {{ __('Change Photo') }}
                        </x-filament::button>
                        <span>{{ __('Change Photo') }}</span>
                    </label>

                    <x-filament::button size='sm' x-on:click="cropAndSave(); $dispatch('close-modal', {id: 'photo-upload-cropper-{{ $getStatePath() }}', save: true})">
                        {{ __('Crop and Save') }}
                    </x-filament::button>
                </div>
            </x-slot>
        </x-filament::modal>
    </div>
</x-forms::field-wrapper>