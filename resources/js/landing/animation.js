import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Inisialisasi animasi Hero pada saat halaman dimuat.
 */
export function initHeroAnimation() {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    
    tl.from('.hero-bg-gradient', { opacity: 0, duration: 1.5 })
      .from('.hero-title', { y: 50, opacity: 0, duration: 1 }, '-=1')
      .from('.hero-subtitle', { y: 30, opacity: 0, duration: 0.8 }, '-=0.8')
      .from('.hero-cta', { y: 20, opacity: 0, duration: 0.6 }, '-=0.6')
      .from('.hero-stats', { y: 20, opacity: 0, duration: 0.6 }, '-=0.5')
      .from('.hero-phone-container', { y: 100, opacity: 0, duration: 1.2, ease: 'back.out(1.2)' }, '-=0.8')
      .from('.float-element', { scale: 0, opacity: 0, duration: 0.8, stagger: 0.15 }, '-=0.6');
}

/**
 * Inisialisasi efek Scroll Reveal untuk elemen berkelas .reveal-section.
 */
export function initScrollReveal() {
    const revealSections = document.querySelectorAll('.reveal-section');
    revealSections.forEach((section) => {
        gsap.fromTo(section, 
            { opacity: 0, y: 50 }, 
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                }
            }
        );
    });
}

/**
 * Inisialisasi parallax latar belakang berdasarkan scroll.
 */
export function initParallax() {
    gsap.utils.toArray('.parallax-element').forEach((el) => {
        const speed = el.dataset.speed || 0.1;
        gsap.to(el, {
            yPercent: -speed * 100,
            ease: 'none',
            scrollTrigger: {
                trigger: el,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    });
}

/**
 * Inisialisasi animasi melayang (floating) untuk elemen dekoratif secara kontinu.
 */
export function initFloatingObjects() {
    gsap.utils.toArray('.float-element').forEach((el) => {
        const randomY = gsap.utils.random(10, 25);
        const randomX = gsap.utils.random(-10, 10);
        const randomDuration = gsap.utils.random(4, 7);
        const randomDelay = gsap.utils.random(0, 2);
        
        gsap.to(el, {
            y: `+=${randomY}`,
            x: `+=${randomX}`,
            duration: randomDuration,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: randomDelay
        });
    });
}

/**
 * Inisialisasi efek mouse-follow parallax 3D untuk mockup ponsel di Hero.
 */
export function initMouseParallax() {
    const container = document.querySelector('.hero-phone-container');
    if (!container) return;
    
    const phone = container.querySelector('.hero-phone-mockup') || container;
    
    window.addEventListener('mousemove', (e) => {
        const { clientX, clientY } = e;
        const { innerWidth, innerHeight } = window;
        
        const xPercent = (clientX / innerWidth) - 0.5;
        const yPercent = (clientY / innerHeight) - 0.5;
        
        gsap.to(phone, {
            rotateY: xPercent * 16,
            rotateX: -yPercent * 16,
            x: xPercent * 20,
            y: yPercent * 20,
            duration: 0.6,
            ease: 'power2.out'
        });
    });
}



/**
 * Inisialisasi timeline alur kerja vertikal dengan progress bar dinamis.
 */
export function initWorkflowTimeline() {
    const timeline = document.querySelector('.workflow-timeline-container');
    const activeLine = document.querySelector('.workflow-progress-active');
    if (!timeline || !activeLine) return;
    
    gsap.fromTo(activeLine, 
        { height: '0%' },
        {
            height: '100%',
            ease: 'none',
            scrollTrigger: {
                trigger: timeline,
                start: 'top 45%',
                end: 'bottom 55%',
                scrub: true
            }
        }
    );
    
    const steps = document.querySelectorAll('.workflow-step');
    steps.forEach((step, index) => {
        gsap.from(step, {
            opacity: 0,
            x: index % 2 === 0 ? -50 : 50,
            duration: 0.8,
            scrollTrigger: {
                trigger: step,
                start: 'top 75%',
                toggleActions: 'play none none none'
            }
        });
    });
}

/**
 * Inisialisasi stagger reveal untuk kartu showcase.
 */
export function initShowcaseCards() {
    const cards = document.querySelectorAll('.showcase-card');
    if (cards.length === 0) return;
    
    gsap.from(cards, {
        opacity: 0,
        y: 45,
        scale: 0.96,
        duration: 0.8,
        stagger: 0.12,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '.showcase-grid',
            start: 'top 80%',
            toggleActions: 'play none none none'
        }
    });
}

/**
 * Inisialisasi transition navbar pada saat scroll.
 */
export function initNavbarScroll() {
    const navbar = document.querySelector('#main-navbar');
    if (!navbar) return;
    
    ScrollTrigger.create({
        start: 'top -50',
        onUpdate: (self) => {
            if (self.direction === 1) {
                navbar.classList.add('navbar-scrolled');
            } else if (self.scroll() < 50) {
                navbar.classList.remove('navbar-scrolled');
            }
        }
    });
}

/**
 * Inisialisasi efek floating particles berbasis HTML5 Canvas di latar belakang Hero.
 */
export function initFloatingParticles() {
    const canvas = document.querySelector('#particle-canvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let particles = [];
    
    const resizeCanvas = () => {
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
    };
    
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 2 + 0.8;
            this.speedX = Math.random() * 0.3 - 0.15;
            this.speedY = Math.random() * 0.3 - 0.15;
            const colors = [
                'rgba(212, 175, 55, 0.25)', 
                'rgba(184, 134, 11, 0.2)', 
                'rgba(230, 190, 138, 0.25)', 
                'rgba(255, 228, 225, 0.2)'
            ];
            this.color = colors[Math.floor(Math.random() * colors.length)];
        }
        
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            
            if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
            if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
        }
        
        draw() {
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    
    const init = () => {
        particles = [];
        const count = Math.min(Math.floor((canvas.width * canvas.height) / 16000), 55);
        for (let i = 0; i < count; i++) {
            particles.push(new Particle());
        }
    };
    
    init();
    
    let animationFrameId;
    const animate = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
        }
        animationFrameId = requestAnimationFrame(animate);
    };
    
    animate();
    
    return () => {
        cancelAnimationFrame(animationFrameId);
        window.removeEventListener('resize', resizeCanvas);
    };
}

/**
 * Inisialisasi efek denyut cahaya pada CTA.
 */
export function initCTAGlow() {
    const cta = document.querySelector('.cta-section');
    const glowOrbs = document.querySelectorAll('.cta-glow-orb');
    if (!cta || glowOrbs.length === 0) return;
    
    gsap.fromTo(glowOrbs, 
        { scale: 0.8, opacity: 0.35 },
        {
            scale: 1.25,
            opacity: 0.6,
            duration: 3,
            stagger: 0.6,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            scrollTrigger: {
                trigger: cta,
                start: 'top 85%'
            }
        }
    );
}

/**
 * Inisialisasi ScrollTrigger untuk menggerakkan bagian preview ponsel di section live preview.
 */
export function initPreviewScroll() {
    const previewSection = document.querySelector('.preview-section');
    const previewPhone = document.querySelector('.preview-phone-mockup');
    const innerScreen = document.querySelector('.preview-inner-screen');
    
    if (!previewSection || !previewPhone || !innerScreen) return;
    
    // Animate mockups entering
    gsap.from(previewPhone, {
        y: 150,
        opacity: 0,
        duration: 1.2,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: previewSection,
            start: 'top 75%',
            toggleActions: 'play none none none'
        }
    });

    // Parallax scrolling for inner screen content
    gsap.to(innerScreen, {
        y: () => -(innerScreen.scrollHeight - innerScreen.parentElement.offsetHeight),
        ease: 'none',
        scrollTrigger: {
            trigger: previewSection,
            start: 'top 20%',
            end: 'bottom 80%',
            scrub: 1.2
        }
    });
}

/**
 * Inisialisasi animasi pada Section Musik Latar (mock player & pulsing controls).
 */
export function initMusicAnimation() {
    const mockPlayer = document.querySelector('.music-player-mock');
    if (!mockPlayer) return;
    
    // Hover parallax effect on mock player card
    mockPlayer.addEventListener('mousemove', (e) => {
        const rect = mockPlayer.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        
        gsap.to(mockPlayer, {
            rotateY: x * 0.08,
            rotateX: -y * 0.08,
            x: x * 0.05,
            y: y * 0.05,
            duration: 0.5,
            ease: 'power2.out'
        });
    });
    
    mockPlayer.addEventListener('mouseleave', () => {
        gsap.to(mockPlayer, {
            rotateY: 0,
            rotateX: 0,
            x: 0,
            y: 0,
            duration: 0.8,
            ease: 'power3.out'
        });
    });
    
    // Pulse effect on play button representation inside landing player mock
    const playBtn = mockPlayer.querySelector('.h-12.w-12');
    if (playBtn) {
        gsap.to(playBtn, {
            scale: 1.08,
            repeat: -1,
            yoyo: true,
            duration: 1.2,
            ease: 'sine.inOut'
        });
    }
}
