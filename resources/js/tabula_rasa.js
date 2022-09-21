require('./bootstrap');

import Cropper from "cropperjs";
import multiselect from './alpinejs/filamentphp/multi-select'
import customPhotoUploadFormComponent from './alpinejs/filamentphp/photo-upload'
import focus from '@alpinejs/focus'

import Alpine from 'alpinejs'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'

window.Alpine = Alpine
window.Cropper = Cropper;

Alpine.data('multiselect', multiselect);
Alpine.plugin(customPhotoUploadFormComponent)
Alpine.plugin(focus)
Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(NotificationsAlpinePlugin)

Alpine.start();