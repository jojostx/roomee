require('./bootstrap');

import Cropper from "cropperjs";

import customPhotoUploadFormComponent from './alpinejs/filamentphp/photo-upload'
import multiselect from './alpinejs/filamentphp/multi-select'
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
 
window.Cropper = Cropper;

Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(focus)
Alpine.plugin(AlpineFloatingUI)
Alpine.plugin(NotificationsAlpinePlugin)

Alpine.plugin(customPhotoUploadFormComponent)
Alpine.data('multiselect', multiselect);

window.Alpine = Alpine

Alpine.start();