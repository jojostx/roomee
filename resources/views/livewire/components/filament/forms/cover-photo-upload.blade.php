<x-forms::field-wrapper 
:id="$getId()" 
:label="$getLabel()" 
:label-sr-only="$isLabelHidden()" 
:helper-text="$getHelperText()" 
:hint="$getHint()" 
:hint-icon="$getHintIcon()" 
:required="$isRequired()" 
:state-path="$getStatePath()"
>
  <div x-bind="cover" class="mb-2 gap-x-6 lg:gap-y-2 sm:grid-cols-2">
    <div>
      <div id="cover_inp" class="overflow-hidden relative flex items-center justify-center px-4 pt-4 pb-4 mt-1 border-2 border-secondary-300 @error('cover_photo') border-danger-300 @enderror border-dashed rounded-md">
        <div class="relative w-full space-y-1 text-center">
          @if (auth()->user()->cover_photo)
          <svg wire:ignore id="cover-svg" class="hidden w-12 h-12 mx-auto text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <div wire:ignore>
            <img src="{{ auth()->user()->coverPhotoPath }}" id="cover_out" class="z-10 block w-full mb-5 rounded-lg shadow-lg" alt="" height="100%" width="100%">
          </div>
          @else
          <svg wire:ignore id="cover-svg" class="w-12 h-12 mx-auto text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <div wire:ignore>
            <img src="" id="cover_out" class="z-10 hidden block w-full mb-5 rounded-lg shadow-lg" alt="">
          </div>
          @endif
          <div class="flex flex-col items-center mt-6 text-sm text-secondary-600">
            <label tabindex="0" for="cover_photo" class="relative flex items-center justify-between px-3 py-2 mb-2 font-semibold leading-4 text-white bg-primary-600 rounded-md shadow-sm cursor-pointer hover:bg-primary-700 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
              <!-- flex items-center justify-between px-3 py-2 text-sm font-semibold leading-4 text-white bg-primary-600 rounded-md shadow-sm cursor-pointer hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 -->
              <div wire:ignore class="mr-1.5 loader" id="loader_cover" style="display: none;">
              </div>
              <span>
                @if (auth()->user()->cover_photo)
                Change cover photo
                @else
                Upload a cover photo
                @endif
              </span>
              <input id="cover_photo" name="cover_photo" type="file" class="sr-only">
            </label>
          </div>
          <p class="text-xs text-secondary-500">
            PNG, JPG, GIF images of up to 5MB
          </p>
        </div>
      </div>
    </div>
  </div>
</x-forms::field-wrapper>