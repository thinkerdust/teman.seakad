<!-- Inline Hero Section -->
<div class="hero-section relative flex flex-col justify-center items-center min-h-[70vh] py-16 px-6 text-center overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-10 pointer-events-none hero-bg" style="background-image: url('{{ themeAsset('hero.background') }}')"></div>
    
    <div class="relative z-10 max-w-2xl mx-auto flex flex-col items-center">
        <span class="hero-subtitle text-xs sm:text-sm uppercase tracking-[0.2em] font-semibold mb-6 inline-block">
            Undangan Pernikahan
        </span>
        
        <div class="hero-names-container my-4 sm:my-6">
            <h1 class="hero-names text-4xl sm:text-5xl md:text-7xl font-bold tracking-wide leading-tight">
                {{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }} & {{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}
            </h1>
        </div>
        
        <div class="hero-details-container mt-6 space-y-4 w-full">
            <div class="h-[1px] w-24 bg-current opacity-30 mx-auto my-4 hero-divider"></div>
            <p class="hero-date text-lg sm:text-xl font-medium tracking-wide">
                {{ $invitationData['reception_date'] ? Carbon\Carbon::parse($invitationData['reception_date'])->translatedFormat('d F Y') : ( $invitationData['akad_date'] ? Carbon\Carbon::parse($invitationData['akad_date'])->translatedFormat('d F Y') : '-' ) }}
            </p>
            <p class="hero-venue text-sm sm:text-base opacity-90 max-w-md mx-auto leading-relaxed">
                {{ $invitationData['venue'] }}
            </p>
            @if($invitationData['description'])
                <p class="hero-description text-xs sm:text-sm italic opacity-75 max-w-md mx-auto mt-8 px-4 leading-relaxed">
                    "{{ $invitationData['description'] }}"
                </p>
            @endif
        </div>
    </div>
</div>

<!-- Separation Divider -->
<div class="flex justify-center items-center py-6 floral-divider">
    <div class="h-[1px] w-24 bg-current opacity-20"></div>
    <span class="mx-4 text-lg">☪</span>
    <div class="h-[1px] w-24 bg-current opacity-20"></div>
</div>

<!-- Mempelai Details -->
<section class="py-16 px-6 text-center space-y-12 border-b border-stone-200/50" {!! themeAnimation('couple') !!}>
    <div class="space-y-2">
        <h2 class="text-2xl font-bold uppercase tracking-widest" style="font-family: var(--theme-font-heading);">Mempelai</h2>
        <div class="h-[1px] w-12 bg-current mx-auto mt-2 opacity-50"></div>
        <p class="text-xs opacity-75 max-w-xs mx-auto leading-relaxed mt-4">
            Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah, perkenankanlah kami menyatukan dua hati dalam ikatan pernikahan yang suci.
        </p>
    </div>

    <!-- Groom details -->
    <div class="space-y-4" data-gsap="fade-up">
        <div class="relative w-28 h-28 mx-auto flex items-center justify-center border border-[var(--theme-secondary)] bg-[var(--theme-surface)] shadow-inner mb-4 overflow-hidden" style="border-radius: 1rem 0 1rem 0;">
            <div class="absolute inset-1 border border-dashed border-[var(--theme-primary)]/40 z-10" style="border-radius: 0.9rem 0 0.9rem 0;"></div>
            @if(!empty($invitationData['groom_photo']))
                <img src="{{ $invitationData['groom_photo'] }}" alt="{{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}" class="h-full w-full object-cover" />
            @else
                <span class="text-4xl font-heading text-[var(--theme-primary)] font-light opacity-80">{{ substr($invitationData['groom_nickname'] ?? $invitationData['groom_name'], 0, 1) }}</span>
            @endif
        </div>
        <div class="font-accent text-4xl text-[var(--theme-primary)]">{{ $invitationData['groom_name'] }}</div>
        <p class="text-xs opacity-60">Putra dari Bapak & Ibu Terbaik</p>
    </div>

    <!-- Divider -->
    @if(themeAsset('ornaments.1'))
        <div class="py-6 pointer-events-none z-10 opacity-30" data-gsap="fade-in">
            <img src="{{ themeAsset('ornaments.1') }}" class="mx-auto max-h-12 object-contain" />
        </div>
    @else
        <div class="flex justify-center items-center py-6" data-gsap="fade-in">
            <div class="h-[1px] w-24 bg-[var(--theme-primary)] opacity-20"></div>
            <span class="mx-4 text-lg text-[var(--theme-primary)] opacity-40">❀</span>
            <div class="h-[1px] w-24 bg-[var(--theme-primary)] opacity-20"></div>
        </div>
    @endif

    <!-- Bride details -->
    <div class="space-y-4" data-gsap="fade-up">
        <div class="relative w-28 h-28 mx-auto flex items-center justify-center border border-[var(--theme-secondary)] bg-[var(--theme-surface)] shadow-inner mb-4 overflow-hidden" style="border-radius: 1rem 0 1rem 0;">
            <div class="absolute inset-1 border border-dashed border-[var(--theme-primary)]/40 z-10" style="border-radius: 0.9rem 0 0.9rem 0;"></div>
            @if(!empty($invitationData['bride_photo']))
                <img src="{{ $invitationData['bride_photo'] }}" alt="{{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}" class="h-full w-full object-cover" />
            @else
                <span class="text-4xl font-heading text-[var(--theme-primary)] font-light opacity-80">{{ substr($invitationData['bride_nickname'] ?? $invitationData['bride_name'], 0, 1) }}</span>
            @endif
        </div>
        <div class="font-accent text-4xl text-[var(--theme-primary)]">{{ $invitationData['bride_name'] }}</div>
        <p class="text-xs opacity-60">Putri dari Bapak & Ibu Terbaik</p>
    </div>
</section>