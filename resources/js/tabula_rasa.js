import './bootstrap';
import multiselect from './alpinejs/filamentphp/multi-select'

import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Tooltip from "@ryangjchandler/alpine-tooltip";
import Persist from '@alpinejs/persist';
import Collapse from '@alpinejs/collapse';

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
    Alpine.plugin(Persist);
    Alpine.plugin(Collapse);
    Alpine.plugin(Tooltip);
    Alpine.plugin(AlpineFloatingUI);

    // 2. Register Custom Data
    Alpine.data('multiselect', multiselect);

    // 3. Register Stores
    Alpine.store('onboarding_steps', {
        show: Alpine.$persist(true).using(sessionStorage),
    });
});
