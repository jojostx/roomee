require('./bootstrap');

import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import multiselect from './alpinejs/multiselect'

window.Alpine = Alpine;

Alpine.plugin(focus)
Alpine.data('multiselect', multiselect);

Alpine.start();