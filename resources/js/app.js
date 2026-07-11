import './bootstrap';

import Alpine from 'alpinejs';

if (typeof window.Alpine === 'undefined') {
    window.Alpine = Alpine;
    Alpine.start();
}
