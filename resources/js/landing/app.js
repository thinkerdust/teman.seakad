import '../bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import {
    initHeroAnimation,
    initScrollReveal,
    initParallax,
    initFloatingObjects,
    initMouseParallax,
    initWorkflowTimeline,
    initShowcaseCards,
    initNavbarScroll,
    initFloatingParticles,
    initCTAGlow,
    initPreviewScroll
} from './animation';

// Daftarkan plugin Alpine.js
Alpine.plugin(persist);

// Daftarkan Alpine data components
Alpine.data('testimonialCarousel', (count = 3) => ({
    active: 0,
    count: count,
    autoplayInterval: null,
    
    init() {
        this.startAutoplay();
    },
    
    next() {
        this.active = (this.active + 1) % this.count;
        this.resetAutoplay();
    },
    
    prev() {
        this.active = (this.active - 1 + this.count) % this.count;
        this.resetAutoplay();
    },
    
    goTo(index) {
        this.active = index;
        this.resetAutoplay();
    },
    
    startAutoplay() {
        this.autoplayInterval = setInterval(() => {
            this.active = (this.active + 1) % this.count;
        }, 6000);
    },
    
    resetAutoplay() {
        clearInterval(this.autoplayInterval);
        this.startAutoplay();
    }
}));

window.Alpine = Alpine;
window.gsap = gsap;

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inisialisasi Lenis Smooth Scroll
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        wheelMultiplier: 1.0,
    });
    
    // Sinkronkan Lenis dengan ScrollTrigger
    lenis.on('scroll', ScrollTrigger.update);
    
    gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
    });
    gsap.ticker.lagSmoothing(0);
    
    window.lenis = lenis;

    // 2. Inisialisasi Animasi GSAP
    initNavbarScroll();
    initHeroAnimation();
    initScrollReveal();
    initParallax();
    initFloatingObjects();
    initMouseParallax();
    initWorkflowTimeline();
    initShowcaseCards();
    initFloatingParticles();
    initCTAGlow();
    initPreviewScroll();
});

// Mulai Alpine.js
Alpine.start();
