<div id="cover-overlay" class="fixed inset-0 z-9999 flex flex-col items-center justify-between bg-gradient-to-b from-neutral-950 via-neutral-900 to-neutral-950 text-white p-8 text-center">
    <!-- Top decorative element -->
    <div class="mt-8">
        <span class="text-xs uppercase tracking-[0.3em] text-amber-400 font-semibold">The Wedding Invitation</span>
        <div class="h-[1px] w-24 bg-gradient-to-r from-transparent via-amber-400 to-transparent mx-auto mt-2"></div>
    </div>

    <!-- Middle content -->
    <div class="space-y-6">
        <div class="font-accent text-6xl sm:text-7xl text-amber-200 animate-fade-in">
            {{ $invitationData['groom_name'] }}
        </div>
        <div class="font-heading text-lg tracking-[0.2em] text-neutral-400">&</div>
        <div class="font-accent text-6xl sm:text-7xl text-amber-200 animate-fade-in">
            {{ $invitationData['bride_name'] }}
        </div>
        
        <div class="space-y-2 mt-8">
            <p class="text-xs text-neutral-500 uppercase tracking-widest">Kepada Yth. Bapak/Ibu/Saudara/i</p>
            <div class="text-lg font-heading text-amber-100 py-2 px-6 bg-amber-400/5 rounded-full inline-block backdrop-blur-md border border-amber-400/20">
                {{ $invitationData['recipient_name'] ?: 'Tamu Undangan' }}
            </div>
        </div>
    </div>

    <!-- Bottom action -->
    <div class="mb-12">
        <button 
            id="btn-open-invitation"
            class="inline-flex items-center gap-2.5 px-8 py-4 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 text-neutral-950 font-bold text-sm uppercase tracking-widest shadow-lg shadow-amber-500/20 hover:scale-105 transition duration-300 active:scale-95"
        >
            <svg class="h-4 w-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            </svg>
            Buka Undangan
        </button>
    </div>
</div>
