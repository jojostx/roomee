<div>
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Contact {{ $this->user->full_name }}
        </p>
    </div>
    <div class="px-4 py-2">
        <div class="space-y-2">
            @foreach ($this->verifiedContactChannels as $channel)
            <a target="_blank" href="{{ $channel->link }}" class="contact-badge">
                <x-dynamic-component :component="'logos.'. $channel->type" class="w-5 h-5 mr-1" />
                <span class="capitalize">Connect <span class="lowercase">via</span> {{ $channel->type }}</span>
            </a>
            @endforeach
        </div>
        <div class="flex justify-end pt-2">
            <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-3 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
        </div>
    </div>
</div>
