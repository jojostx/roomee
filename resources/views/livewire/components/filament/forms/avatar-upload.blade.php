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
    <div x-bind="avatar" class="flex flex-col items-center justify-start px-4 py-4 mb-6 rounded-lg sm:flex-row bg-primary-50 sm:py-6">
      <div class="mb-4 sm:w-2/5 sm:mb-0">
        <div class="flex flex-col items-center justify-center mt-1">
          <div wire:ignore class="block w-24 h-24 mb-4 overflow-hidden bg-primary-200 rounded-full">
            @if (auth()->user()->avatar)
            <img id="avatar_img" src="{{ auth()->user()->avatarPath }}" alt="avatar image" class="h-full" width="100%" height="100%">
            @else
            <img id="avatar_img" src="" alt="avatar image" class="hidden h-full">
            @endif
            <svg id="pl-ava" class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <label wire:ignore tabindex="0" for="avatar_photo" class="flex items-center justify-between px-3 py-2 text-sm font-semibold leading-4 text-white bg-indigo-600 rounded-md shadow-sm cursor-pointer hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <div class="mr-1.5 loader" id="loader_avatar" style="display: none;">
            </div>
            <span>Change</span>
            <input id="avatar_photo" wire:target="handleAvatarUpload" wire:loading.attr="disabled" name="avatar_photo" type="file" class="sr-only" accept=".jpg, .jpeg, .png" />
          </label>
          @error('avatar')
          <x-livewire.error-text>{{ $message }}</x-livewire.error-text>
          @enderror
        </div>
      </div>
      <div class="flex flex-col justify-center w-full pt-4 md:pt-2">
        <div class="flex justify-start gap-4 mb-6">
          <div class="w-1/2 mr-6">
            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->firstname) }}</span>
            <label class="block text-sm text-gray-700">First Name</label>
          </div>
          <div>
            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->lastname) }}</span>
            <label class="block text-sm text-gray-700">Last Name</label>
          </div>
        </div>
        <div class="flex justify-start gap-4">
          <div class="w-1/2 mr-6 overflow-hidden">
            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->email) }}</span>
            <label class="block text-sm text-gray-700">Email Address</label>
          </div>
          <div>
            <span class="text-lg font-semibold">{{ ucfirst(auth()->user()->gender) }}</span>
            <label class="block text-sm text-gray-700">Gender</label>
          </div>
        </div>
      </div>
    </div>
</x-forms::field-wrapper>