import './bootstrap';
import multiselect from './alpinejs/filamentphp/multi-select'

import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Tooltip from "@ryangjchandler/alpine-tooltip";
// Note: @alpinejs/collapse and @alpinejs/persist are bundled and registered internally by Livewire — do not import or re-register them here.

// Register chat store early so x-show in the layout can read it before Alpine processes the DOM.
document.addEventListener('alpine:init', () => {
    const autoOpen = document.body.dataset.chatAutoOpen === 'true';

    Alpine.store('chat', {
        open: autoOpen,
        previousUrl: autoOpen ? '/dashboard' : null,
        pendingRoomId: null,
        openedFromStandaloneRoute: autoOpen,

        toggle() {
            this.open ? this.close() : this.openModal();
        },

        openModal(roomId = null) {
            if (roomId) {
                this.pendingRoomId = roomId;
            }

            if (!this.open) {
                this.previousUrl = window.location.href;
            }

            this.openedFromStandaloneRoute = false;
            this.open = true;
        },

        close() {
            const targetUrl = this.previousUrl;
            const shouldNavigate = this.openedFromStandaloneRoute && !!targetUrl;

            this.open = false;
            this.pendingRoomId = null;
            this.openedFromStandaloneRoute = false;

            if (!targetUrl) {
                return;
            }

            if (shouldNavigate) {
                window.location.assign(targetUrl);

                return;
            }

            history.pushState({}, '', targetUrl);
        },
    });

    window.addEventListener('open-chat-room', (event) => {
        Alpine.store('chat').openModal(event.detail?.roomId ?? null);
    });
});

document.addEventListener('livewire:init', () => {
    const Alpine = window.Alpine;

    // 1. Register non-bundled plugins (collapse, persist, focus, intersect are bundled by Livewire)
    Alpine.plugin(Tooltip);
    Alpine.plugin(AlpineFloatingUI);

    // 2. Register Custom Data
    Alpine.data('multiselect', multiselect);

    // 3. Register Stores
    const storedOnboarding = sessionStorage.getItem('_x_onboarding_steps_show');
    Alpine.store('onboarding_steps', {
        show: storedOnboarding !== null ? JSON.parse(storedOnboarding) : true,
    });
    Alpine.effect(() => {
        sessionStorage.setItem(
            '_x_onboarding_steps_show',
            JSON.stringify(Alpine.store('onboarding_steps').show)
        );
    });
});
