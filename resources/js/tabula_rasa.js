require('./bootstrap');

import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import multiselect from './alpinejs/filamentphp/multiselect'

window.Alpine = Alpine;

Alpine.data('multiselect', multiselect);
Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(focus)

Alpine.start();