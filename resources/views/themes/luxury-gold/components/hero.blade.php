<div id="cover-overlay" class="fixed inset-0 z-9999 flex flex-col items-center justify-between p-8 text-center" style="background: radial-gradient(circle at center, #141414 0%, #0d0d0d 100%); color: #f7f5f0;">
    <!-- Top decorative element -->
    <div class="mt-8">
        <span class="text-xs uppercase tracking-[0.3em] font-semibold" style="color: #d4af37;">Undangan Pernikahan</span>
        <div class="h-[1px] w-24 mx-auto mt-2" style="background-color: #d4af37;"></div>
    </div>

    <!-- Middle content -->
    <div class="space-y-6">
        <div class="text-6xl sm:text-7xl animate-fade-in" style="font-family: 'Cinzel', serif; color: #e5c158; background: linear-gradient(135deg, #f3e5ab 0%, #d4af37 50%, #aa7c11 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            {{ $invitationData['groom_name'] }}
        </div>
        <div class="text-lg tracking-[0.2em] opacity-70">&</div>
        <div class="text-6xl sm:text-7xl animate-fade-in" style="font-family: 'Cinzel', serif; color: #e5c158; background: linear-gradient(135deg, #f3e5ab 0%, #d4af37 50%, #aa7c11 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            {{ $invitationData['bride_name'] }}
        </div>
        
        <div class="space-y-2 mt-8">
            <p class="text-xs uppercase tracking-widest opacity-60">Kepada Yth. Bapak/Ibu/Saudara/i</p>
            <div class="text-lg py-2 px-6 rounded-full inline-block backdrop-blur-md border" style="background-color: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2); font-family: 'Cinzel', serif; color: #f3e5ab;">
                {{ $invitationData['recipient_name'] ?: 'Tamu Undangan' }}
            </div>
        </div>
    </div>

    <!-- Bottom action -->
    <div class="mb-12">
        <button 
            id="btn-open-invitation"
            class="inline-flex items-center gap-2.5 px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest shadow-lg transition duration-300 hover:scale-105 active:scale-95 cursor-pointer"
            style="background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%); color: #0d0d0d;"
        >
            <svg class="h-4 w-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            </svg>
            Buka Undangan
        </button>
    </div>
</div>