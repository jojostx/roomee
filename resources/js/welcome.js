import Flickity from 'flickity'

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import intersect from '@alpinejs/intersect'

window.Alpine = Alpine;

Alpine.plugin(intersect)
Alpine.plugin(focus)

Alpine.start();


if (document.querySelector('.testimonial-carousel')) {

    let flkty = new Flickity('.testimonial-carousel', {
        cellAlign: 'center',
        wrapAround: 'true',
        autoPlay: false,
    })
};

if (document.querySelector('.steps-carousel')) {

    let flkty2 = new Flickity('.steps-carousel', {
        cellAlign: 'center',
        wrapAround: 'true',
        autoPlay: true,
        pauseAutoPlayOnHover: true,
        prevNextButtons: false,
        pageDots: false,

    })
};