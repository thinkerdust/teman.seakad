<footer class="py-20 px-6 text-center space-y-10 relative border-t border-[var(--theme-secondary)]" {!! themeAnimation('footer') !!}>
    <!-- Decorative element -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 flex items-center justify-center -mt-3">
        <div class="h-1 w-12 bg-[var(--theme-primary)] rounded-full shadow-[0_0_10px_var(--theme-primary)]"></div>
    </div>

    <div class="space-y-4 pt-4">
        <p class="text-[10px] text-[var(--theme-text)] opacity-70 uppercase tracking-widest leading-relaxed max-w-xs mx-auto">
            Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.
        </p>
    </div>

    <div class="space-y-5">
        <p class="text-[10px] font-semibold text-[var(--theme-primary)] uppercase tracking-[0.3em]">Kami yang berbahagia,</p>
        <div class="text-5xl font-accent gold-shimmer-text">
            {{ $invitationData['groom_name'] }} & {{ $invitationData['bride_name'] }}
        </div>
    </div>

    <div class="pt-12 mt-12 border-t border-[var(--theme-secondary)]">
        <a href="https://teman-seakad.com" target="_blank" class="text-[9px] text-[var(--theme-text)] opacity-40 hover:opacity-100 hover:text-[var(--theme-accent)] tracking-[0.25em] uppercase transition duration-300">
            Created with passion by Teman Seakad
        </a>
    </div>
</footer>