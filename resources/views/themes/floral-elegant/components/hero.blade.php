<div id="cover-overlay" {!! themeAnimation('hero') !!} class="hero-section z-[9999] flex flex-col items-center justify-between p-8 text-center transition-transform duration-1000 {{ ($themeConfig['layout']['hero'] ?? 'fullscreen') === 'fullscreen' ? 'fixed inset-0' : 'relative min-h-screen' }}" style="background-image: url('{{ themeAsset('hero.background') }}'); background-size: cover; background-position: center;">
    <!-- Dynamic Ornaments -->
    @if(themeAsset('ornaments.0'))
        <div class="absolute top-0 left-0 right-0 pointer-events-none z-0 opacity-20">
            <img src="{{ themeAsset('ornaments.0') }}" class="w-full max-h-48 object-contain object-top" />
        </div>
    @endif
    @if(themeAsset('ornaments.1'))
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none z-0 opacity-20">
            <img src="{{ themeAsset('ornaments.1') }}" class="w-full max-h-48 object-contain object-bottom" />
        </div>
    @endif

    <!-- Floral Particles -->
    <div class="floral-particles-container" id="particles-container">
        <!-- Particles generated via JS below -->
    </div>

    <!-- Top decorative element -->
    <div class="mt-12 z-10 relative" data-gsap="fade-down">
        <span class="hero-subtitle text-[10px] font-semibold">The Wedding Celebration</span>
        <div class="h-[1px] w-16 mx-auto mt-3 hero-divider opacity-50"></div>
    </div>

    <!-- Middle content -->
    <div class="space-y-6 z-10 relative">
        <div class="hero-names text-6xl sm:text-7xl" data-gsap="fade-in">
            {{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}
        </div>
        <div class="font-heading text-lg tracking-[0.2em] opacity-60" data-gsap="fade-in">&</div>
        <div class="hero-names text-6xl sm:text-7xl" data-gsap="fade-in">
            {{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}
        </div>
        
        <div class="space-y-3 mt-10" data-gsap="fade-up">
            <p class="text-[10px] uppercase tracking-[0.2em] opacity-60">Kepada Yth. Bapak/Ibu/Saudara/i</p>
            <div class="text-base font-heading py-2 px-8 rounded-full inline-block backdrop-blur-md bg-white/40 border border-white/60 shadow-sm text-[var(--theme-text)]">
                {{ $invitationData['recipient_name'] ?: 'Tamu Undangan' }}
            </div>
        </div>
    </div>

    <!-- Bottom action -->
    <div class="mb-12 z-10 relative" data-gsap="fade-up">
        <button 
            id="btn-open-invitation"
            class="inline-flex items-center gap-3 px-8 py-4 rounded-full font-bold text-[11px] uppercase tracking-widest shadow-lg transition duration-300 hover:scale-105 active:scale-95 cursor-pointer bg-[var(--theme-primary)] text-white hover:shadow-xl"
        >
            <svg class="h-4 w-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 10l3 3 3-3" />
            </svg>
            Buka Undangan
        </button>
    </div>
</div>

<script>
    // Simple script to generate floating petals
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('particles-container');
        if(container) {
            for(let i = 0; i < 15; i++) {
                const petal = document.createElement('div');
                petal.className = 'floral-petal';
                petal.style.left = Math.random() * 100 + 'vw';
                petal.style.animationDelay = (Math.random() * 10) + 's';
                petal.style.animationDuration = (15 + Math.random() * 10) + 's';
                petal.style.opacity = (0.2 + Math.random() * 0.4).toString();
                container.appendChild(petal);
            }
        }
    });
</script>