import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import { gsap } from 'gsap';

// Register Alpine.js plugins
Alpine.plugin(persist);

window.Alpine = Alpine;
window.gsap = gsap;

// Inisialisasi scroll listener untuk efek navbar
document.addEventListener('DOMContentLoaded', () => {
    // GSAP page entrance animation
    gsap.from('.hero-animate', {
        opacity: 0,
        y: 30,
        duration: 1,
        stagger: 0.2,
        ease: 'power3.out'
    });
});

// Start Alpine.js
Alpine.start();
