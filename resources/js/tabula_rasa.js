import './bootstrap';
import multiselect from './alpinejs/filamentphp/multi-select'

import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Tooltip from "@ryangjchandler/alpine-tooltip";

document.addEventListener('livewire:init', () => {
    const Alpine = window.Alpine;

    // 1. Register non-bundled plugins
    Alpine.plugin(Tooltip);
    Alpine.plugin(AlpineFloatingUI);

    // 2. Register Custom Data
    Alpine.data('multiselect', multiselect);

    // 3. Register Stores (using nextTick to ensure $persist is ready)
    Alpine.nextTick(() => {
        if (typeof Alpine.$persist === 'function') {
            Alpine.store('onboarding_steps', {
                show: Alpine.$persist(true).using(sessionStorage),
            });
        } else {
            console.error("Alpine $persist is still missing. Ensure 'alpinejs/persist' isn't being blocked.");
        }
    });
});
