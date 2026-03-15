import './bootstrap';
import multiselect from './alpinejs/filamentphp/multi-select'

import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Tooltip from "@ryangjchandler/alpine-tooltip";
import Collapse from '@alpinejs/collapse';
// Note: @alpinejs/persist is bundled and registered internally by Livewire — do not import or re-register it here.

// Register chat store early so x-show in the layout can read it before Alpine processes the DOM.
document.addEventListener('alpine:init', () => {
    const autoOpen = document.body.dataset.chatAutoOpen === 'true';

    Alpine.store('chat', {
        open: autoOpen,
        previousUrl: autoOpen ? '/dashboard' : null,

        toggle() {
            this.open ? this.close() : this.openModal();
        },

        openModal() {
            if (!this.open) {
                this.previousUrl = window.location.href;
                this.open = true;
            }
        },

        close() {
            this.open = false;
            if (this.previousUrl) {
                history.pushState({}, '', this.previousUrl);
            }
        },
    });
});

document.addEventListener('livewire:init', () => {
    const Alpine = window.Alpine;

    // 1. Register non-bundled plugins
    Alpine.plugin(Collapse);
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
