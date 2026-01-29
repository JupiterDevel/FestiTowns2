import './bootstrap';

import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap';
import { ToastService } from './toast';

window.Alpine = Alpine;
window.bootstrap = bootstrap;

// Initialize Toast Service
document.addEventListener('DOMContentLoaded', function() {
    if (!window.toast) {
        window.toast = new ToastService();
    }
});

Alpine.start();
