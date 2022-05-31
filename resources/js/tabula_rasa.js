require('./bootstrap');

import Cropper from "cropperjs";
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import multiselect from './alpinejs/filamentphp/multi-select'
import customPhotoUploadFormComponent from './alpinejs/filamentphp/photo-upload'

window.Alpine = Alpine;
window.Cropper = Cropper;

Alpine.data('multi-select', multiselect);

Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(customPhotoUploadFormComponent)
Alpine.plugin(focus)

Alpine.start();