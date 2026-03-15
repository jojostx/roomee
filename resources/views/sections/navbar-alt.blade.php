<div class="w-full px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center flex-shrink-0">
            <a href="{{ route('home') }}" aria-label="application logo" title="application logo">
                <x-application-logo class="block w-auto h-10 text-secondary-600 fill-current" />
            </a>
        </div>

        <!-- Settings Dropdown -->
        <div class="flex items-center">
            @unless (request()->routeIs('dashboard'))
            <a href="{{ route('dashboard') }}" style="border-width: 1.5px;" class="items-center justify-start hidden px-2 py-1 text-sm transition duration-150 ease-in-out border rounded-md text-primary-700 border-primary-700 md:flex sm:text-base hover:text-primary-700 hover:bg-primary-200 focus:outline-none focus:bg-primary-200 focus:text-primary-700">Dashboard</a>
            @endunless

            @auth
            @php
                $unreadChatCount = \App\Models\ChatMessage::query()
                    ->whereHas('chatRoom', fn ($q) => $q->where('user_a_id', auth()->id())->orWhere('user_b_id', auth()->id()))
                    ->where('sender_id', '!=', auth()->id())
                    ->whereNull('read_at')
                    ->count();
            @endphp
            <a href="{{ route('chat.index') }}" class="relative ml-2 flex items-center justify-center w-9 h-9 rounded-lg text-secondary-500 hover:bg-secondary-100 transition-colors" title="Messages">
                <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                @if ($unreadChatCount > 0)
                    <span class="absolute top-0.5 right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-primary-600 text-[9px] font-bold text-white leading-none">
                        {{ $unreadChatCount > 9 ? '9+' : $unreadChatCount }}
                    </span>
                @endif
            </a>
            @endauth

            <div class="mx-2">
                @livewire('notifications')
            </div>

            <x-nav-menu />
        </div>
    </div>
</div>