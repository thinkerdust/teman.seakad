<section class="py-24 px-6 text-center space-y-16 border-b border-neutral-850 bg-black relative overflow-hidden" {!! themeAnimation('couple') !!}>
    <!-- Subtle spotlight -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-neutral-900/30 to-transparent pointer-events-none"></div>

    <div class="space-y-4 relative z-10">
        <h2 class="font-heading text-sm text-[var(--theme-accent)] uppercase tracking-[0.5em]">The Wedding Of</h2>
        <div class="h-[1px] w-24 bg-[var(--theme-accent)] mx-auto mt-2 opacity-50"></div>
        <p class="text-[10px] text-neutral-400 max-w-xs mx-auto leading-relaxed mt-6 uppercase tracking-widest">
            Two souls with but a single thought<br/>Two hearts that beat as one
        </p>
    </div>

    <!-- Groom details -->
    <div class="space-y-4 relative z-10 cinematic-card p-8 border-t border-[var(--theme-secondary)] bg-gradient-to-b from-neutral-900/50 to-transparent" data-gsap="fade-up">
        <div class="relative w-24 h-24 mx-auto rounded-full flex items-center justify-center border border-neutral-800 bg-neutral-900 shadow-inner mb-4 overflow-hidden">
            <div class="absolute inset-1 rounded-full border border-dashed border-[var(--theme-primary)]/40 z-10"></div>
            @if(!empty($invitationData['groom_photo']))
                <img src="{{ $invitationData['groom_photo'] }}" alt="{{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}" class="h-full w-full object-cover" />
            @else
                <span class="text-3xl font-heading text-[var(--theme-accent)] font-light opacity-80">{{ substr($invitationData['groom_nickname'] ?? $invitationData['groom_name'], 0, 1) }}</span>
            @endif
        </div>
        <div class="font-accent text-6xl text-white">{{ $invitationData['groom_name'] }}</div>
        <div class="h-[1px] w-12 bg-neutral-800 mx-auto"></div>
        <p class="text-[10px] text-neutral-500 uppercase tracking-widest">Putra dari Bapak & Ibu Terbaik</p>
    </div>

    <!-- Divider -->
    @if(themeAsset('ornaments.1'))
        <div class="py-6 pointer-events-none z-10 opacity-30" data-gsap="fade-in">
            <img src="{{ themeAsset('ornaments.1') }}" class="mx-auto max-h-12 object-contain" />
        </div>
    @else
        <div class="font-heading text-3xl text-[var(--theme-accent)] my-8 relative z-10 font-light" data-gsap="fade-in">&</div>
    @endif

    <!-- Bride details -->
    <div class="space-y-4 relative z-10 cinematic-card p-8 border-b border-[var(--theme-secondary)] bg-gradient-to-t from-neutral-900/50 to-transparent" data-gsap="fade-up">
        <div class="relative w-24 h-24 mx-auto rounded-full flex items-center justify-center border border-neutral-800 bg-neutral-900 shadow-inner mb-4 overflow-hidden">
            <div class="absolute inset-1 rounded-full border border-dashed border-[var(--theme-primary)]/40 z-10"></div>
            @if(!empty($invitationData['bride_photo']))
                <img src="{{ $invitationData['bride_photo'] }}" alt="{{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}" class="h-full w-full object-cover" />
            @else
                <span class="text-3xl font-heading text-[var(--theme-accent)] font-light opacity-80">{{ substr($invitationData['bride_nickname'] ?? $invitationData['bride_name'], 0, 1) }}</span>
            @endif
        </div>
        <div class="font-accent text-6xl text-white">{{ $invitationData['bride_name'] }}</div>
        <div class="h-[1px] w-12 bg-neutral-800 mx-auto"></div>
        <p class="text-[10px] text-neutral-500 uppercase tracking-widest">Putri dari Bapak & Ibu Terbaik</p>
    </div>
</section>
