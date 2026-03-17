@php
    $otherUser = $chatRoom->otherParticipant(auth()->user());
@endphp

<div class="w-11/12 max-w-2xl py-10 mx-auto md:py-16">
    <div class="px-6 py-8 text-center bg-white border shadow-sm rounded-2xl border-secondary-200">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-primary-50">
            <x-heroicon-o-chat-bubble-left-right class="w-8 h-8 text-primary-600" />
        </div>
        <h1 class="text-2xl font-semibold text-secondary-900">Chat with {{ $otherUser?->full_name ?? 'your match' }}</h1>
        <p class="mt-3 text-sm leading-6 text-secondary-600">
            This route exists as a fallback entry point. Roomee opens the actual conversation in the chat drawer and keeps contact sharing there too.
        </p>
        <div class="flex flex-col items-center justify-center gap-3 mt-6 sm:flex-row">
            <button
                type="button"
                x-on:click="$store.chat.openModal('{{ $chatRoom->id }}')"
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-colors rounded-xl bg-primary-600 hover:bg-primary-700 focus:outline-hidden focus:ring-2 focus:ring-primary-500">
                Open Chat
            </button>
            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold transition-colors border rounded-xl text-secondary-700 border-secondary-300 hover:bg-secondary-50 focus:outline-hidden focus:ring-2 focus:ring-secondary-300">
                Back to Dashboard
            </a>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
