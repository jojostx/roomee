require('./bootstrap');

import Cropper from "cropperjs";
import Alpine from 'alpinejs';

import customPhotoUploadFormComponent from './alpinejs/filamentphp/photo-upload'
import multiselect from './alpinejs/filamentphp/multi-select'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import focus from '@alpinejs/focus'
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import Tooltip from "@ryangjchandler/alpine-tooltip";

window.Cropper = Cropper;

Alpine.plugin(focus);
Alpine.plugin(Tooltip);
Alpine.plugin(AlpineFloatingUI);
Alpine.plugin(FormsAlpinePlugin);
Alpine.plugin(NotificationsAlpinePlugin);

Alpine.plugin(customPhotoUploadFormComponent)
Alpine.data('multiselect', multiselect);

window.Alpine = Alpine

Alpine.start();