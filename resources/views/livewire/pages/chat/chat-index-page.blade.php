<div class="w-full min-h-full bg-secondary-50">
    <x-page-header>
        <h1 class="text-sm font-bold uppercase text-secondary-500">Messages</h1>
        <h1 class="font-bold">Your Chats</h1>
    </x-page-header>

    <div class="px-3 py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
        @if ($this->chatRooms->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 mb-4 rounded-full bg-secondary-100 flex items-center justify-center">
                    <x-heroicon-o-chat-bubble-left-right class="w-8 h-8 text-secondary-400" />
                </div>
                <h2 class="text-lg font-semibold text-secondary-700">No conversations yet</h2>
                <p class="mt-1 text-sm text-secondary-500">
                    Accept or receive a roommate request to start chatting.
                </p>
                <a href="{{ route('roommate-requests') }}"
                   class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    View Requests
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($this->chatRooms as $room)
                    @php
                        $other = $room->otherParticipant(auth()->user());
                        $latestMessage = $room->messages->first();
                        $unreadCount = $room->messages()
                            ->where('sender_id', '!=', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                    <a href="{{ route('chat.room', $room) }}"
                       class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-secondary-100 hover:border-primary-300 hover:shadow-md transition-all duration-150">

                        <div class="relative flex-shrink-0">
                            <img src="{{ $other?->avatar_path }}"
                                 alt="{{ $other?->full_name }}"
                                 class="w-12 h-12 rounded-full object-cover ring-2 ring-secondary-200">
                            @if ($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-secondary-800 truncate">
                                    {{ $other?->full_name }}
                                </span>
                                @if ($latestMessage)
                                    <span class="text-xs text-secondary-400 ml-2 flex-shrink-0">
                                        {{ $latestMessage->created_at->diffForHumans(short: true) }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-0.5 text-xs text-secondary-500 truncate">
                                @if ($latestMessage)
                                    {{ $latestMessage->message }}
                                @else
                                    <span class="italic">No messages yet</span>
                                @endif
                            </p>
                        </div>

                        @if ($room->hasBothSharedContacts())
                            <span class="flex-shrink-0 inline-flex items-center gap-1 text-xs font-medium text-success-600 bg-success-50 px-2 py-1 rounded-full border border-success-200">
                                <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                                Contacts shared
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
