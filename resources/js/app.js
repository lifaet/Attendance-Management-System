import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Import attendance UX so it's bundled with app.js
import './attendance.js';
