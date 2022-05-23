require('./bootstrap');

import Cropper from "cropperjs";
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import multiselect from './alpinejs/filamentphp/multiselect'
import customFileUploadFormComponent from './alpinejs/filamentphp/fileupload'

window.Alpine = Alpine;
window.Cropper = Cropper;

Alpine.data('multiselect', multiselect);

Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(customFileUploadFormComponent)
Alpine.plugin(focus)

Alpine.start();