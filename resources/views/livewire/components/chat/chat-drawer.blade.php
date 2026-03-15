<div
    x-data="{ listOpen: @js(!$this->activeChatRoomId) }"
    class="relative flex flex-col w-full h-full overflow-hidden bg-secondary-50"
>

    {{-- ============================================================ --}}
    {{-- Slide-over chat list panel                                   --}}
    {{-- ============================================================ --}}
    <div
        x-show="listOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="absolute inset-y-0 left-0 z-20 flex flex-col w-72 sm:w-80 bg-white border-r border-secondary-200 shadow-2xl overflow-hidden"
    >
        {{-- Panel header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-secondary-200 flex-shrink-0">
            <h2 class="text-sm font-semibold text-secondary-800">Messages</h2>
            <button
                @click="listOpen = false"
                class="p-1 rounded-lg text-secondary-400 hover:bg-secondary-100 transition-colors"
                aria-label="Close conversations"
            >
                <x-heroicon-o-x-mark class="w-4 h-4" />
            </button>
        </div>

        {{-- Room list --}}
        <div class="flex-1 overflow-y-auto divide-y divide-secondary-100">
            @forelse ($this->chatRooms as $room)
                @php
                    $other        = $room->otherParticipant(auth()->user());
                    $latestMsg    = $room->messages->first();
                    $unreadCount  = $room->messages()
                        ->where('sender_id', '!=', auth()->id())
                        ->whereNull('read_at')
                        ->count();
                @endphp
                <button
                    wire:click="selectRoom('{{ $room->id }}')"
                    @click="listOpen = false"
                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-colors
                        {{ $this->activeChatRoomId === $room->id
                            ? 'bg-primary-50 hover:bg-primary-100'
                            : 'bg-white hover:bg-secondary-50' }}"
                >
                    <div class="relative flex-shrink-0">
                        <img
                            src="{{ $other?->avatar_path }}"
                            alt="{{ $other?->full_name }}"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-secondary-200"
                        >
                        @if ($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary-600 text-[9px] font-bold text-white">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold text-secondary-800 truncate">
                                {{ $other?->full_name }}
                            </span>
                            @if ($latestMsg)
                                <span class="text-[10px] text-secondary-400 flex-shrink-0">
                                    {{ $latestMsg->created_at->diffForHumans(short: true) }}
                                </span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-xs text-secondary-500 truncate">
                            @if ($latestMsg)
                                {{ $latestMsg->message }}
                            @else
                                <span class="italic">No messages yet</span>
                            @endif
                        </p>
                    </div>
                </button>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                    <div class="w-12 h-12 mb-3 rounded-full bg-secondary-100 flex items-center justify-center">
                        <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 text-secondary-400" />
                    </div>
                    <p class="text-sm font-semibold text-secondary-700">No conversations yet</p>
                    <p class="mt-1 text-xs text-secondary-500">Accept or receive a roommate request to start chatting.</p>
                </div>
            @endforelse

            {{-- Infinite scroll sentinel --}}
            @if ($this->hasMoreRooms)
                <div
                    x-intersect.once="$wire.loadMoreRooms()"
                    wire:loading.class="opacity-0"
                    wire:target="loadMoreRooms"
                    class="flex items-center justify-center py-4"
                >
                    <div class="w-5 h-5 border-2 border-primary-300 border-t-primary-600 rounded-full animate-spin"></div>
                </div>
            @endif
        </div>
    </div>

    {{-- Backdrop — closes panel when tapped outside --}}
    <div
        x-show="listOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="listOpen = false"
        class="absolute inset-0 z-10 bg-black/40"
    ></div>

    {{-- ============================================================ --}}
    {{-- Chat box                                                     --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col w-full h-full overflow-hidden">

        @if ($this->activeChatRoom)

            {{-- Chat header --}}
            <div class="flex-shrink-0 flex items-center justify-between px-4 py-3 bg-white border-b border-secondary-200 shadow-sm z-10">
                <div class="flex items-center gap-3">
                    {{-- Toggle list panel --}}
                    <button
                        @click="listOpen = !listOpen"
                        class="p-1.5 rounded-lg text-secondary-500 hover:bg-secondary-100 transition-colors"
                        title="Conversations"
                    >
                        <x-heroicon-o-queue-list class="w-5 h-5" />
                    </button>

                    <img
                        src="{{ $this->otherUser?->avatar_path }}"
                        alt="{{ $this->otherUser?->full_name }}"
                        class="w-9 h-9 rounded-full object-cover ring-2 ring-secondary-200"
                    >
                    <div>
                        <p class="text-sm font-semibold text-secondary-800 leading-tight">
                            {{ $this->otherUser?->full_name }}
                        </p>
                        <p class="hidden sm:block text-xs text-secondary-400">
                            {{ $this->otherUser?->course?->name }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Contact sharing controls --}}
                    @if ($this->hasBothSharedContacts)
                        <button
                            wire:click="openContactModal"
                            class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1.5 text-xs font-semibold text-white rounded-lg bg-success-600 hover:bg-success-700 transition-colors focus:outline-none focus:ring-2 focus:ring-success-500">
                            <x-heroicon-s-phone class="w-4 h-4" />
                            <span class="hidden sm:inline">Contact Info</span>
                        </button>
                    @elseif (!$this->hasCurrentUserSharedContacts)
                        <button
                            wire:click="mountAction('shareContacts')"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1.5 text-xs font-semibold text-primary-700 rounded-lg border border-primary-300 bg-primary-50 hover:bg-primary-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-60">
                            <x-heroicon-o-share class="w-4 h-4" />
                            <span class="hidden sm:inline">Share Contacts</span>
                        </button>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1.5 text-xs font-medium text-secondary-500 rounded-lg bg-secondary-100">
                            <x-heroicon-o-clock class="w-4 h-4" />
                            <span class="hidden sm:inline">Waiting for {{ $this->otherUser?->first_name }}</span>
                        </span>
                        <button
                            wire:click="mountAction('unshareContacts')"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1.5 text-xs font-semibold text-danger-700 rounded-lg border border-danger-300 bg-danger-50 hover:bg-danger-100 transition-colors focus:outline-none focus:ring-2 focus:ring-danger-500 disabled:opacity-60">
                            <x-heroicon-o-x-mark class="w-4 h-4" />
                            <span class="hidden sm:inline">Undo</span>
                        </button>
                    @endif

                    {{-- Close overlay --}}
                    <button
                        @click="$store.chat.close()"
                        class="p-1.5 rounded-lg text-secondary-500 hover:bg-secondary-100 transition-colors"
                        title="Close"
                    >
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>
            </div>

            {{-- Contact-sharing consent banner --}}
            @php
                $otherHasShared = $this->activeChatRoom->hasUserSharedContacts($this->otherUser ?? new \App\Models\User());
                $meHasShared    = $this->hasCurrentUserSharedContacts;
            @endphp
            @if ($otherHasShared && !$meHasShared)
                <div class="flex-shrink-0 flex items-center justify-between gap-3 px-4 py-3 bg-primary-50 border-b border-primary-200">
                    <div class="flex items-center gap-2 text-sm text-primary-800">
                        <x-heroicon-s-information-circle class="w-5 h-5 flex-shrink-0 text-primary-600" />
                        <span><strong>{{ $this->otherUser?->first_name }}</strong> wants to share contact info with you.</span>
                    </div>
                    <button
                        wire:click="mountAction('shareContacts')"
                        class="flex-shrink-0 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white rounded-lg bg-primary-600 hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <x-heroicon-s-check class="w-3.5 h-3.5" />
                        Accept
                    </button>
                </div>
            @endif

            {{-- Messages --}}
            <div
                class="flex-1 overflow-y-auto px-4 py-4 space-y-3"
                x-ref="messageList"
                x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
                @message-sent.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
            >
                @forelse ($this->chatMessages as $message)
                    @php $isMine = $message->sender_id === auth()->id(); @endphp
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2">
                        @if (!$isMine)
                            <img
                                src="{{ $message->sender?->avatar_path }}"
                                alt="{{ $message->sender?->full_name }}"
                                class="w-7 h-7 rounded-full object-cover flex-shrink-0 mb-0.5"
                            >
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
                            <img
                                src="{{ auth()->user()->avatar_path }}"
                                alt="You"
                                class="w-7 h-7 rounded-full object-cover flex-shrink-0 mb-0.5"
                            >
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
                            x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 160) + 'px'"
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="flex-shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary-600 text-white hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-50"
                    >
                        <x-heroicon-s-paper-airplane class="w-5 h-5" />
                    </button>
                </form>
            </div>

        @else

            {{-- Placeholder — no conversation selected --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center px-8">
                <div class="w-16 h-16 mb-4 rounded-full bg-secondary-100 flex items-center justify-center">
                    <x-heroicon-o-chat-bubble-left-right class="w-8 h-8 text-secondary-400" />
                </div>
                <h3 class="text-base font-semibold text-secondary-700">Your messages</h3>
                <p class="mt-1 text-sm text-secondary-500">
                    Select a conversation from the list to start chatting.
                </p>
                <button
                    @click="listOpen = true"
                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary-700 rounded-lg border border-primary-300 bg-primary-50 hover:bg-primary-100 transition-colors"
                >
                    <x-heroicon-o-queue-list class="w-4 h-4" />
                    View Conversations
                </button>
                <button
                    @click="$store.chat.close()"
                    class="mt-3 text-xs text-secondary-400 hover:text-secondary-600 transition-colors"
                >
                    Close
                </button>
            </div>

        @endif
    </div>

    <x-filament-actions::modals />

    @script
    <script>
        $wire.on('message-sent', () => {
            window.dispatchEvent(new CustomEvent('message-sent'));
        });
    </script>
    @endscript
</div>
