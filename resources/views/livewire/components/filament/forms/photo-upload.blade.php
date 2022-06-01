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
        x-data="customPhotoUploadFormComponent({
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
        <div @class([
                    'overflow-hidden relative flex flex-col items-center justify-center w-full',
                    'w-32 h-32 mb-2 overflow-hidden bg-gray-200 rounded-full' => $isAvatar(),
                    'w-full h-64 mb-2 overflow-hidden rounded-lg shadow-lg' => ! $isAvatar()
                ])> 
            <div>
                <img 
                    src="{{ ($imageSrc = $getImageUrl()) ? $imageSrc : ($isAvatar() ? 'https://www.gravatar.com/avatar/00?d=mp' : 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=N+A') }}" 
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
            </div>
            <div class="absolute inset-0 z-10 flex items-center justify-center w-full bg-gray-700/50">
                <label x-show="!isUploading" for="{{ $getId() }}_input" tabindex="0" 
                    class="text-white border-[3px] border-white rounded-full cursor-pointer"
                    title="Change your avatar">
                    <svg class="w-8 h-8" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 10.414v3.585a1 1 0 0 1-2 0v-3.585l-1.293 1.293a1 1 0 0 1-1.414-1.415l3-3a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1-1.414 1.415L14 10.414zM9 18a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2H9z" fill="currentColor" fill-rule="evenodd"></path>
                    </svg>
                </label>
                <svg x-cloak x-show="isUploading" role="status" class="text-gray-200 w-9 h-9 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
            </div>
        </div>

        <!-- trap focus on modal when open -->
        <x-filament::modal width="2xl" id="photo-upload-cropper-{{ $getStatePath() }}" heading="Crop Image" x-on:close-modal.window="if($event.detail.id == 'photo-upload-cropper-{{ $getStatePath() }}' ) { resetCropper(); }">
            <div class="relative bg-white rounded-lg dark:bg-gray-700">
                <div class="flex flex-col items-center justify-center mt-1">
                    <div class="flex items-center justify-center w-full overflow-hidden rounded-lg">
                        <img 
                            id="cropCanvas"
                            x-ref="cropCanvas"
                            x-cloak 
                            src="" 
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
        </x-filament::modal>
    </div>
</x-forms::field-wrapper>