require('./bootstrap');

import Cropper from "cropperjs";
import Alpine from 'alpinejs';

import customPhotoUploadFormComponent from './alpinejs/filamentphp/photo-upload'
import multiselect from './alpinejs/filamentphp/multi-select'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import focus from '@alpinejs/focus'
import collapse from '@alpinejs/collapse'
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Tooltip from "@ryangjchandler/alpine-tooltip";
import persist from '@alpinejs/persist'

window.Cropper = Cropper;

Alpine.plugin(focus);
Alpine.plugin(Tooltip);
Alpine.plugin(AlpineFloatingUI);
Alpine.plugin(collapse);
Alpine.plugin(FormsAlpinePlugin);
Alpine.plugin(NotificationsAlpinePlugin);
Alpine.plugin(customPhotoUploadFormComponent);
Alpine.plugin(persist)

window.Alpine = Alpine;

Alpine.data('multiselect', multiselect);

Alpine.store('onboarding_steps', {
   'show': Alpine.$persist(true).using(sessionStorage),
});

Alpine.start();