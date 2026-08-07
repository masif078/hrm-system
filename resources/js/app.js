import './bootstrap';

import 'bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {

    const toggle = document.getElementById('menu-toggle');

    if (toggle) {
        toggle.addEventListener('click', function () {
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    }

});