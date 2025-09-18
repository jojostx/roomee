<div>
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Report <span class="user-name">{{ $user->full_name }}</span>?
        </p>
    </div>
    <div class="py-1 pt-2 text-base">
        <ul class="px-2">
            @foreach ($reports as $key => $value)
            <li>
                <label for="report_{{ $key }}" class="flex items-center justify-between px-2 py-2 border-b cursor-pointer">
                    <p>{{ $value }}</p>
                    <input type="checkbox" id="report_{{ $key }}" value="{{ $key }}" wire:model="selectedReports">
                </label>
            </li>
            @endforeach
        </ul>
        <div class="flex justify-end px-4">
            <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-4 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
            <button wire:click="submit" class="px-1 py-2 font-semibold text-danger-500 hover:text-danger-700 focus:text-danger-700 focus:outline-none">Report</button>
        </div>
        @if ($errors->any())
        <div class="border-t bg-danger-100 border-danger-500">
            <div class="px-4 py-2 text-danger-600">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>