<section class="py-20 px-6 text-center space-y-14 border-b border-[var(--theme-secondary)] relative" {!! themeAnimation('couple') !!}>
    <!-- Decorative border corner -->
    <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-[var(--theme-primary)] opacity-30"></div>
    <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-[var(--theme-primary)] opacity-30"></div>

    <div class="space-y-4">
        <h2 class="font-heading text-xl text-[var(--theme-primary)] font-bold uppercase tracking-[0.3em]">Mempelai</h2>
        <div class="h-[1px] w-16 bg-[var(--theme-accent)] mx-auto mt-2 opacity-50"></div>
        <p class="text-[11px] text-[var(--theme-text)] max-w-xs mx-auto leading-relaxed mt-4 opacity-80">
            Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah, perkenankanlah kami menyatukan dua hati.
        </p>
    </div>

    <!-- Groom details -->
    <div class="space-y-4 luxury-card p-6 mx-4" data-gsap="fade-up">
        <div class="relative w-24 h-24 mx-auto flex items-center justify-center border border-[var(--theme-secondary)] bg-black/40 shadow-inner mb-4 overflow-hidden" style="border-radius: 4px;">
            <div class="absolute inset-1 border border-dashed border-[var(--theme-primary)]/40 z-10" style="border-radius: 2px;"></div>
            @if(!empty($invitationData['groom_photo']))
                <img src="{{ $invitationData['groom_photo'] }}" alt="{{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}" class="h-full w-full object-cover" />
            @else
                <span class="text-3xl font-heading text-[var(--theme-primary)] font-light opacity-80">{{ substr($invitationData['groom_nickname'] ?? $invitationData['groom_name'], 0, 1) }}</span>
            @endif
        </div>
        <div class="font-accent text-5xl text-[var(--theme-accent)]">{{ $invitationData['groom_name'] }}</div>
        <div class="h-[1px] w-8 bg-[var(--theme-secondary)] mx-auto"></div>
        <p class="text-[10px] text-[var(--theme-text)] uppercase tracking-widest opacity-60">Putra dari Bapak & Ibu Terbaik</p>
    </div>

    <!-- Divider -->
    @if(themeAsset('ornaments.1'))
        <div class="py-6 pointer-events-none z-10 opacity-30" data-gsap="fade-in">
            <img src="{{ themeAsset('ornaments.1') }}" class="mx-auto max-h-12 object-contain" />
        </div>
    @else
        <div class="font-heading text-3xl text-[var(--theme-primary)] my-6 gold-shimmer-text" data-gsap="fade-in">&</div>
    @endif

    <!-- Bride details -->
    <div class="space-y-4 luxury-card p-6 mx-4" data-gsap="fade-up">
        <div class="relative w-24 h-24 mx-auto flex items-center justify-center border border-[var(--theme-secondary)] bg-black/40 shadow-inner mb-4 overflow-hidden" style="border-radius: 4px;">
            <div class="absolute inset-1 border border-dashed border-[var(--theme-primary)]/40 z-10" style="border-radius: 2px;"></div>
            @if(!empty($invitationData['bride_photo']))
                <img src="{{ $invitationData['bride_photo'] }}" alt="{{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}" class="h-full w-full object-cover" />
            @else
                <span class="text-3xl font-heading text-[var(--theme-primary)] font-light opacity-80">{{ substr($invitationData['bride_nickname'] ?? $invitationData['bride_name'], 0, 1) }}</span>
            @endif
        </div>
        <div class="font-accent text-5xl text-[var(--theme-accent)]">{{ $invitationData['bride_name'] }}</div>
        <div class="h-[1px] w-8 bg-[var(--theme-secondary)] mx-auto"></div>
        <p class="text-[10px] text-[var(--theme-text)] uppercase tracking-widest opacity-60">Putri dari Bapak & Ibu Terbaik</p>
    </div>
</section>