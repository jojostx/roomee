<div
    x-data
    class="w-full flex flex-col bg-secondary-50"
    style="height: calc(var(--vh, 1vh) * 100 - 4rem);">

    {{-- Header --}}
    <div class="flex-shrink-0 flex items-center justify-between px-4 py-3 bg-white border-b border-secondary-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('chat.index') }}"
               class="p-1.5 rounded-lg text-secondary-500 hover:bg-secondary-100 transition-colors">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
            </a>
            <img src="{{ $this->otherUser?->avatar_path }}"
                 alt="{{ $this->otherUser?->full_name }}"
                 class="w-9 h-9 rounded-full object-cover ring-2 ring-secondary-200">
            <div>
                <p class="text-sm font-semibold text-secondary-800 leading-tight">
                    {{ $this->otherUser?->full_name }}
                </p>
                <p class="text-xs text-secondary-400">{{ $this->otherUser?->course?->name }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if ($this->hasBothSharedContacts)
                <button
                    wire:click="openContactModal"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white rounded-lg bg-success-600 hover:bg-success-700 transition-colors focus:outline-none focus:ring-2 focus:ring-success-500">
                    <x-heroicon-s-phone class="w-4 h-4" />
                    Contact Info
                </button>
            @elseif (!$this->hasCurrentUserSharedContacts)
                <button
                    wire:click="shareContacts"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-primary-700 rounded-lg border border-primary-300 bg-primary-50 hover:bg-primary-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-60">
                    <x-heroicon-o-share class="w-4 h-4" />
                    Share Contacts
                </button>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-secondary-500 rounded-lg bg-secondary-100">
                    <x-heroicon-o-clock class="w-4 h-4" />
                    Waiting for {{ $this->otherUser?->first_name }}
                </span>
            @endif
        </div>
    </div>

    {{-- Contact-sharing consent banner (shown when the other user has already shared but current user hasn't) --}}
    @php
        $otherHasShared = $chatRoom->hasUserSharedContacts($this->otherUser ?? new \App\Models\User());
        $meHasShared    = $this->hasCurrentUserSharedContacts;
    @endphp
    @if ($otherHasShared && !$meHasShared)
        <div class="flex-shrink-0 flex items-center justify-between gap-3 px-4 py-3 bg-primary-50 border-b border-primary-200">
            <div class="flex items-center gap-2 text-sm text-primary-800">
                <x-heroicon-s-information-circle class="w-5 h-5 flex-shrink-0 text-primary-600" />
                <span><strong>{{ $this->otherUser?->first_name }}</strong> wants to share contact info with you.</span>
            </div>
            <button
                wire:click="shareContacts"
                class="flex-shrink-0 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white rounded-lg bg-primary-600 hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500">
                <x-heroicon-s-check class="w-3.5 h-3.5" />
                Accept
            </button>
        </div>
    @endif

    {{-- Message list --}}
    <div
        class="flex-1 overflow-y-auto px-4 py-4 space-y-3"
        x-ref="messageList"
        x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
        @message-sent.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })">

        @forelse ($this->chatMessages as $message)
            @php $isMine = $message->sender_id === auth()->id(); @endphp
            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2">
                @if (!$isMine)
                    <img src="{{ $message->sender?->avatar_path }}"
                         alt="{{ $message->sender?->full_name }}"
                         class="w-7 h-7 rounded-full object-cover flex-shrink-0 mb-0.5">
                @endif

                <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                        {{ $isMine
                            ? 'bg-primary-600 text-white rounded-br-sm'
                            : 'bg-white text-secondary-800 border border-secondary-200 rounded-bl-sm shadow-sm' }}">
                        {{ $message->message }}
                    </div>
                    <p class="mt-1 text-[10px] {{ $isMine ? 'text-right text-secondary-400' : 'text-secondary-400' }}">
                        {{ $message->created_at->format('g:i A') }}
                        @if ($isMine && $message->read_at)
                            · Read
                        @endif
                    </p>
                </div>

                @if ($isMine)
                    <img src="{{ auth()->user()->avatar_path }}"
                         alt="You"
                         class="w-7 h-7 rounded-full object-cover flex-shrink-0 mb-0.5">
                @endif
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 mb-3 rounded-full bg-secondary-100 flex items-center justify-center">
                    <x-heroicon-o-chat-bubble-oval-left-ellipsis class="w-7 h-7 text-secondary-400" />
                </div>
                <p class="text-sm text-secondary-500">No messages yet. Say hello!</p>
            </div>
        @endforelse
    </div>

    {{-- Message input --}}
    <div class="flex-shrink-0 border-t border-secondary-200 bg-white px-4 py-3">
        <form wire:submit="sendMessage" class="flex items-end gap-3">
            <div class="flex-1">
                <textarea
                    wire:model="newMessage"
                    rows="1"
                    placeholder="Type a message…"
                    class="block w-full resize-none rounded-xl border border-secondary-300 bg-secondary-50 px-4 py-2.5 text-sm text-secondary-900 placeholder-secondary-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary-500 transition-colors"
                    x-data
                    x-on:keydown.enter.prevent="if (!$event.shiftKey) { $wire.sendMessage() }"
                    x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px'">
                </textarea>
            </div>
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="flex-shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary-600 text-white hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-50">
                <x-heroicon-s-paper-airplane class="w-5 h-5" />
            </button>
        </form>
    </div>

    @push('scripts')
    <script>
        // Dispatch a custom event when Livewire refreshes so the message list auto-scrolls.
        document.addEventListener('livewire:init', () => {
            Livewire.on('message-sent', () => {
                window.dispatchEvent(new CustomEvent('message-sent'));
            });
        });
    </script>
    @endpush
</div>
