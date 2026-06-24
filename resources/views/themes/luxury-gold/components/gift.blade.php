<section class="py-16 px-6 border-b border-stone-200/50 text-center space-y-8" data-animation="fade-up" x-data="{ open: false }">
    <div class="space-y-2">
        <h2 class="text-2xl font-bold uppercase tracking-widest" style="font-family: var(--font-heading);">Kado Digital</h2>
        <div class="h-[1px] w-12 bg-current mx-auto mt-2 opacity-50"></div>
        <p class="text-xs opacity-75 max-w-xs mx-auto mt-4 leading-relaxed">
            Doa restu Anda merupakan karunia terindah bagi kami. Namun jika Anda ingin memberikan tanda kasih, Anda dapat mengirimkannya secara digital.
        </p>
    </div>

    <button 
        @click="open = !open" 
        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-current text-xs font-bold uppercase tracking-wider hover:bg-black/5 transition cursor-pointer"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
        </svg>
        Kirim Kado Digital
    </button>

    <div 
        x-show="open" 
        x-cloak
        x-transition
        class="space-y-4 max-w-xs mx-auto pt-4"
    >
        @if(isset($themeConfig['gifts']) && is_array($themeConfig['gifts']))
            @foreach($themeConfig['gifts'] as $gift)
                <div class="bg-white border border-stone-200/80 rounded-2xl p-5 text-center space-y-2 shadow-sm">
                    <span class="text-xs font-bold opacity-60 uppercase tracking-widest">{{ $gift['bank_name'] }}</span>
                    <p class="text-lg font-bold text-stone-850">{{ $gift['account_number'] }}</p>
                    <p class="text-[10px] opacity-50">a.n. {{ $gift['owner'] }}</p>
                </div>
            @endforeach
        @else
            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 text-center space-y-2 shadow-sm">
                <span class="text-xs font-bold opacity-60 uppercase tracking-widest">Bank Transfer</span>
                <p class="text-lg font-bold text-stone-850">123-456-789</p>
                <p class="text-[10px] opacity-50">a.n. Mempelai</p>
            </div>
        @endif
    </div>
</section>