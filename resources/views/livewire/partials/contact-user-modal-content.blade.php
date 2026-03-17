<div class="space-y-2 px-4 py-2">
    @foreach ($channels as $channel)
    <a target="_blank" href="{{ $channel->link }}" class="contact-badge">
        <x-dynamic-component :component="'logos.'. $channel->type" class="w-5 h-5 mr-1" />
        <span class="capitalize">Connect <span class="lowercase">via</span> {{ $channel->type }}</span>
    </a>
    @endforeach
</div>
