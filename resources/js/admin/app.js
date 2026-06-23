import '../bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Register Alpine.js plugins
Alpine.plugin(persist);

window.Alpine = Alpine;

// Import administrative components
import './users';
import './menus';
import './themes';
import './invitations';
import './guests';
import './invitationContents';

// Start Alpine.js
Alpine.start();
