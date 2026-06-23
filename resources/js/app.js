import './bootstrap';
import { createApp, defineAsyncComponent, h } from 'vue';
import { gsap } from 'gsap';
import RsvpForm from './invitation/components/RsvpForm.vue';

// Penanganan cover overlay (GSAP transition)
const btnOpen = document.getElementById('btn-open-invitation');
if (btnOpen) {
    btnOpen.addEventListener('click', () => {
        // Izinkan scroll body halaman
        document.body.classList.remove('overflow-hidden');
        
        // Animasi GSAP slide-up dan fade-out cover overlay
        gsap.to('#cover-overlay', {
            y: '-100%',
            opacity: 0,
            duration: 1.2,
            ease: 'power3.inOut',
            onComplete: () => {
                const overlay = document.getElementById('cover-overlay');
                if (overlay) {
                    overlay.remove();
                }
            }
        });
    });
}

// Inisialisasi aplikasi undangan publik jika container ada dan data tersedia
if (document.getElementById('app') && window.invitationData) {
    const app = createApp({
        render() {
            const themeFolder = window.invitationData.theme.folder;
            const currentSlug = window.location.pathname.split('/').filter(Boolean).pop() || '';
            
            // Memuat template komponen secara dinamis berdasarkan folder tema
            const TemplateComponent = defineAsyncComponent({
                loader: () => import(`./invitation/templates/${themeFolder}/App.vue`),
                loadingComponent: {
                    template: `
                        <div style="display: flex; height: 100vh; align-items: center; justify-content: center; background-color: #faf9f6;">
                            <div style="text-align: center;">
                                <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #4f46e5; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                                <p style="margin-top: 16px; font-size: 14px; font-weight: 600; color: #64748b;">Memuat Tema Undangan...</p>
                            </div>
                        </div>
                        <style>
                            @keyframes spin {
                                0% { transform: rotate(0deg); }
                                100% { transform: rotate(360deg); }
                            }
                        </style>
                    `
                },
                errorComponent: {
                    template: `
                        <div style="display: flex; height: 100vh; align-items: center; justify-content: center; background-color: #faf9f6; padding: 24px;">
                            <div style="max-width: 400px; text-align: center; background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0;">
                                <div style="color: #ef4444; font-weight: 700; font-size: 18px; margin-bottom: 8px;">Gagal Memuat Tema</div>
                                <p style="font-size: 14px; color: #64748b; margin: 0;">Template tema tidak ditemukan atau gagal dimuat oleh sistem.</p>
                            </div>
                        </div>
                    `
                },
                timeout: 10000
            });
            
            return h('div', { class: 'public-invitation-container' }, [
                // Render komponen utama tema undangan
                h(TemplateComponent, {
                    groom_name: window.invitationData.groom_name,
                    bride_name: window.invitationData.bride_name,
                    event_date: window.invitationData.reception_date || window.invitationData.akad_date,
                    venue: window.invitationData.venue,
                    address: window.invitationData.address,
                    maps_url: window.invitationData.maps_url,
                    description: window.invitationData.description,
                    gallery: window.invitationData.gallery || [],
                    music: window.invitationData.music || null,
                    story: window.invitationData.story || [],
                    events: window.invitationData.events || []
                }),
                // Render form RSVP global di bagian bawah
                h(RsvpForm, {
                    slug: currentSlug
                })
            ]);
        }
    });
    
    app.mount('#app');
}
