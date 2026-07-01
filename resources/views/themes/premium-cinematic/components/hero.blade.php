<div id="cover-overlay" {!! themeAnimation('hero') !!} class="hero-section z-[9999] flex flex-col items-center justify-between p-8 text-center transition-opacity duration-1000 {{ ($themeConfig['layout']['hero'] ?? 'fullscreen') === 'fullscreen' ? 'fixed inset-0' : 'relative min-h-screen' }}" style="background-image: url('{{ themeAsset('hero.background') }}'); background-size: cover; background-position: center;">
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
    
    <!-- Cinematic Film Grain Overlay -->
    <div class="film-grain-overlay"></div>

    <!-- Cinematic Letterbox (Top & Bottom) -->
    <div class="cinematic-letterbox w-full h-full absolute inset-0 pointer-events-none z-50"></div>

    <!-- Spotlight Gradient -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-neutral-800/40 via-neutral-950/80 to-black z-0 pointer-events-none"></div>

    <!-- Top decorative element -->
    <div class="mt-12 z-10 relative" data-gsap="fade-down">
        <span class="hero-subtitle text-[9px] font-medium tracking-[0.5em] text-[var(--theme-accent)]">A Cinematic Wedding</span>
        <div class="h-[1px] w-12 mx-auto mt-4 bg-[var(--theme-accent)] opacity-40"></div>
    </div>

    <!-- Middle content -->
    <div class="space-y-4 z-10 relative">
        <div class="hero-names text-6xl sm:text-7xl font-accent text-white" data-gsap="cinematic-text">
            {{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}
        </div>
        <div class="font-heading text-sm tracking-[0.4em] text-[var(--theme-accent)] my-2" data-gsap="fade-in">and</div>
        <div class="hero-names text-6xl sm:text-7xl font-accent text-white" data-gsap="cinematic-text">
            {{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}
        </div>
        
        <div class="space-y-4 mt-16" data-gsap="fade-up">
            <p class="text-[9px] uppercase tracking-[0.3em] text-neutral-400">Kepada Yth. Bapak/Ibu/Saudara/i</p>
            <div class="text-sm font-heading py-2 px-8 inline-block border-b border-[var(--theme-accent)] text-white tracking-[0.15em] bg-gradient-to-t from-neutral-900/50 to-transparent">
                {{ $invitationData['recipient_name'] ?: 'Tamu Undangan' }}
            </div>
        </div>
    </div>

    <!-- Bottom action -->
    <div class="mb-12 z-10 relative" data-gsap="fade-up">
        <button 
            id="btn-open-invitation"
            class="inline-flex items-center gap-3 px-8 py-4 bg-transparent border border-[var(--theme-accent)] text-[var(--theme-accent)] font-medium text-[10px] uppercase tracking-[0.2em] transition duration-500 hover:bg-[var(--theme-accent)] hover:text-black cursor-pointer"
        >
            <svg class="h-3 w-3 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Play Invitation
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Trigger letterbox effect on load
        setTimeout(() => {
            const letterbox = document.querySelector('.cinematic-letterbox');
            if (letterbox) letterbox.classList.add('active');
        }, 100);
    });
</script>
