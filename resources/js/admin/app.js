import '../bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Register Alpine.js plugins
Alpine.plugin(persist);

window.Alpine = Alpine;

// Import administrative components
import './users';

// Start Alpine.js
Alpine.start();
