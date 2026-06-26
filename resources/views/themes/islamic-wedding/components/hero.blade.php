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
    
    <!-- Islamic Pattern Background -->
    <div class="islamic-pattern-bg"></div>

    <!-- Top decorative element -->
    <div class="mt-12 z-10 relative" data-gsap="fade-down">
        <div class="arabic-bismillah text-3xl mb-4">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</div>
        <span class="hero-subtitle text-[10px] font-bold">Walimatul 'Urs</span>
        <div class="h-[2px] w-20 mx-auto mt-3 bg-[var(--theme-accent)] opacity-60"></div>
    </div>

    <!-- Middle content -->
    <div class="space-y-6 z-10 relative">
        <div class="hero-names text-5xl sm:text-6xl" data-gsap="fade-in">
            {{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}
        </div>
        <div class="font-heading text-xl text-[var(--theme-accent)] opacity-80" data-gsap="fade-in">&</div>
        <div class="hero-names text-5xl sm:text-6xl" data-gsap="fade-in">
            {{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}
        </div>
        
        <div class="space-y-3 mt-10" data-gsap="fade-up">
            <p class="text-[10px] uppercase tracking-[0.1em] opacity-70">Kepada Yth. Bapak/Ibu/Saudara/i</p>
            <div class="text-base font-heading py-2.5 px-8 inline-block backdrop-blur-md bg-white/70 border-x-2 border-[var(--theme-accent)] text-[var(--theme-text)] font-bold">
                {{ $invitationData['recipient_name'] ?: 'Tamu Kehormatan' }}
            </div>
        </div>
    </div>

    <!-- Bottom action -->
    <div class="mb-12 z-10 relative" data-gsap="fade-up">
        <button 
            id="btn-open-invitation"
            class="inline-flex items-center gap-3 px-8 py-4 bg-[var(--theme-primary)] text-white font-bold text-[11px] uppercase tracking-widest shadow-lg transition duration-300 hover:brightness-95 hover:opacity-90 cursor-pointer"
            style="border-radius: 1rem 0 1rem 0;"
        >
            <svg class="h-4 w-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
            Buka Undangan
        </button>
    </div>
</div>