import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    // Jalankan initAnimations jika elemen dengan data-animation ditemukan dan invitation-opened dikirim
    initThemeAnimations();
});

// Dengarkan custom event 'invitation-opened' jika ada cover overlay
window.addEventListener('invitation-opened', () => {
    initThemeAnimations();
});

export function initThemeAnimations() {
    // 1. Inisialisasi Opening/Hero Animation
    const hero = document.querySelector('[data-animation][data-animation*="reveal"], [data-animation*="bloom"], [data-animation*="fade-slide"]');
    if (hero && !hero.classList.contains('gsap-initiated')) {
        hero.classList.add('gsap-initiated');
        const animationType = hero.getAttribute('data-animation');
        const duration = parseFloat(hero.getAttribute('data-duration') || '2000') / 1000;
        
        // Target elemen internal di dalam hero (seperti judul, nama pengantin, dll)
        const title = hero.querySelector('[data-gsap="fade-down"]');
        const names = hero.querySelectorAll('[data-gsap="fade-in"]');
        const content = hero.querySelector('[data-gsap="fade-up"]');
        const button = hero.querySelector('#btn-open-invitation');
        
        const tl = gsap.timeline();
        
        if (animationType === 'cinematic-reveal') {
            tl.from(hero, { opacity: 0, scale: 1.05, duration: duration, ease: 'power2.out' });
            if (title) tl.from(title, { y: -30, opacity: 0, duration: 0.8, ease: 'power2.out' }, '-=1.2');
            if (names.length) tl.from(names, { y: 20, opacity: 0, duration: 1, stagger: 0.3, ease: 'power2.out' }, '-=0.8');
            if (content) tl.from(content, { y: 30, opacity: 0, duration: 0.8, ease: 'power2.out' }, '-=0.6');
            if (button) tl.from(button, { scale: 0.8, opacity: 0, duration: 0.6, ease: 'back.out(1.7)' }, '-=0.4');
        } else if (animationType === 'fade-slide') {
            tl.from(hero, { opacity: 0, y: 50, duration: duration, ease: 'power3.out' });
            if (names.length) tl.from(names, { opacity: 0, x: -30, duration: 0.8, stagger: 0.2 }, '-=0.5');
            if (button) tl.from(button, { opacity: 0, y: 20, duration: 0.5 }, '-=0.3');
        } else {
            tl.from(hero, { opacity: 0, duration: duration });
        }
    }

    // 2. Inisialisasi Section Scroll Animation
    const sections = document.querySelectorAll('section[data-animation]');
    sections.forEach(section => {
        const anim = section.getAttribute('data-animation');
        const duration = parseFloat(section.getAttribute('data-duration') || '1200') / 1000;
        
        // Cegah re-initialization jika sudah berjalan
        if (section.classList.contains('gsap-initiated')) return;
        section.classList.add('gsap-initiated');
        
        let fromVars = {
            scrollTrigger: {
                trigger: section,
                start: 'top 85%',
            },
            opacity: 0,
            duration: duration,
            ease: 'power2.out'
        };

        if (anim === 'fade-up') {
            fromVars.y = 40;
        } else if (anim === 'slide-stagger' || anim === 'slide') {
            fromVars.x = -50;
        } else if (anim === 'zoom-in' || anim === 'zoom') {
            fromVars.scale = 0.85;
        } else if (anim === 'parallax-reveal') {
            fromVars.y = 60;
            fromVars.scale = 0.95;
        }

        gsap.from(section, fromVars);

        // Sub-elements stagger reveal inside section if present
        const animateItems = section.querySelectorAll('.story-card, .event-card, .gallery-item-wrapper');
        if (animateItems.length > 0) {
            gsap.from(animateItems, {
                scrollTrigger: {
                    trigger: section,
                    start: 'top 75%'
                },
                y: 30,
                opacity: 0,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power2.out'
            });
        }
    });
}
