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
            state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')') }},
            acceptedFileTypes: {{ json_encode($getAcceptedFileTypes()) }},
            isAvatar: {{ $isAvatar() ? 'true' : 'false' }},
            imageCropAspectRatio: {{ ($aspectRatio = $getImageCropAspectRatio()) ? $aspectRatio : 'null' }},
            imagePreviewHeight: {{ ($height = $getImagePreviewHeight()) ? $height : 'null' }},
            maxSize: {{ ($size = $getMaxSize()) ? $size : 'null' }},
            minSize: {{ ($size = $getMinSize()) ? $size : 'null' }},
            minCroppedWidth: {{ ($width = $getMinCroppedWidth) ? $width : 'null' }},
            maxCroppedWidth: {{ ($width = $getMaxCroppedWidth) ? $width : 'null' }},
            minCroppedHeight: {{ ($height = $getMinCroppedHeight()) ? $height : 'null' }},
            defaultImageUrl: {{ ($imageSrc = $getImageUrl()) ? "'{$imageSrc}'" : 'null' }},
            deleteUploadedFileUsing: async (fileKey) => {
                return await $wire.deleteUploadedFile('{{ $getStatePath() }}', fileKey)
            },
            uploadUsing: (fileKey, file, success, error, progress) => {
                $wire.upload(`{{ $getStatePath() }}.${fileKey}`, file, () => {
                    success(fileKey)
                }, error, progress)
            }
            })" 
            {!! ($id=$getId()) ? "id=\" {$id}\"" : null !!} 
            {{ $attributes->merge($getExtraAttributes())->class([
                'filament-forms-file-upload-component',
                'w-32 relative' => $isAvatar(),
            ]) }} 
            style="min-height: {{ $isAvatar() ? '8em' : '4.75em' }}" 
            {{ $getExtraAlpineAttributeBag() }} 
            wire:ignore 
        >
        <!-- if the field is avatar then show avatar box else show default -->
        <div class="relative flex flex-col items-center justify-center w-full space-y-1"> 
            <label @class([
                    'overflow-hidden cursor-pointer',
                    'w-24 h-24 mb-4 overflow-hidden bg-gray-200 rounded-full' => $isAvatar(),
                    'w-full h-64 mb-2 overflow-hidden rounded-lg shadow-lg' => ! $isAvatar()
                ]) 
                title="Change your avatar">
                <img 
                    src="{{ ($imageSrc = $getImageUrl()) ? "'{$imageSrc}'" : ($isAvatar() ? 'https://www.gravatar.com/avatar/00?d=mp' : 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=N+A') }}" 
                    x-ref="poster" 
                    alt="{{ $getAltText() }}" 
                    width="100%">
                
                <input 
                    id="{{ $getId() }}_input" 
                    x-ref="input" 
                    x-on:change="handleFileInputChange('photo-upload-cropper-{{ $getStatePath() }}')" 
                    {{ $isDisabled() ? 'disabled' : '' }} 
                    {{ $getExtraInputAttributeBag() }} 
                    class="sr-only" 
                    type="file" 
                    accept=".jpg, .jpeg, .png" />
            </label>

            <label for="{{ $getId() }}_input" class="underline cursor-pointer hover:text-primary-800" title="Change your avatar">
                {{ __('Upload Photo') }}
            </label>
        </div>


        <!-- trap focus on modal when open -->
        <x-livewire.support.modal.index width="2xl" id="photo-upload-cropper-{{ $getStatePath() }}" heading="Crop Image" x-on:close-modal.window="if($event.detail.id == 'photo-upload-cropper-{{ $getStatePath() }}' ) { resetCropper(); }">
            <div class="relative bg-white rounded-lg dark:bg-gray-700">
                <div class="flex flex-col items-center justify-center mt-1">
                    <div class="flex items-center justify-center w-full overflow-hidden rounded-lg">
                        <img 
                            id="cropCanvas"
                            x-ref="cropCanvas"
                            x-cloak 
                            src="{{ ($imageSrc = $getImageUrl()) ? "'{$imageSrc}'" : ($isAvatar() ? 'https://www.gravatar.com/avatar/00?d=mp' : 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=N+A') }}" 
                            class="w-full"
                            alt="crop canvas image" 
                            width="100%">
                    </div>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end space-x-2 rtl:space-x-reverse" x-cloak x-show="croppable">
                    <x-filament::button size='sm' color="secondary" x-on:click="resetCropper(); $dispatch('close-modal', {id: 'photo-upload-cropper-{{ $getStatePath() }}', save: false})">
                        {{ __('Cancel') }}
                    </x-filament::button>

                    <x-filament::button size='sm' x-on:click="cropAndSave(); $dispatch('close-modal', {id: 'photo-upload-cropper-{{ $getStatePath() }}', save: true})">
                        {{ __('Crop and Save') }}
                    </x-filament::button>
                </div>
            </x-slot>
        </x-livewire.support.modal.index>
    </div>
</x-forms::field-wrapper>