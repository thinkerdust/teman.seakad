<footer class="py-24 px-6 text-center space-y-12 bg-black border-t border-neutral-850 relative" {!! themeAnimation('footer') !!}>
    <!-- Cinematic flare -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-md h-[1px] bg-gradient-to-r from-transparent via-[var(--theme-accent)] to-transparent opacity-30"></div>

    <div class="space-y-4 relative z-10">
        <p class="text-[10px] text-neutral-500 uppercase tracking-[0.2em] leading-relaxed max-w-xs mx-auto">
            Merupakan suatu kehormatan apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.
        </p>
    </div>

    <div class="space-y-6 relative z-10">
        <p class="text-[9px] font-semibold text-[var(--theme-accent)] uppercase tracking-[0.4em]">With Love,</p>
        <div class="text-6xl font-accent text-white" style="text-shadow: 0 4px 20px rgba(0,0,0,0.8);">
            {{ $invitationData['groom_name'] }} & {{ $invitationData['bride_name'] }}
        </div>
    </div>

    <div class="pt-16 mt-16 border-t border-neutral-900 relative z-10">
        <a href="https://teman-seakad.com" target="_blank" class="text-[9px] text-neutral-600 hover:text-[var(--theme-accent)] tracking-[0.3em] uppercase transition duration-500">
            Directed by Teman Seakad
        </a>
    </div>
</footer>
