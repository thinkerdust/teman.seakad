{{-- 
    Gift Section — Premium Digital Gift Experience (Phase 2.1)
    
    Features:
    - Gift icon illustration (floating animation)
    - Elegant toggle button
    - Premium gift cards
    - Theme token styling
--}}

@php
    $themeFolder = $invitation->theme->folder;
@endphp

<section class="py-12 px-6 text-center space-y-6 relative overflow-hidden" style="border-bottom: 1px solid var(--theme-secondary);" {!! themeAnimation('gift') !!} x-data="{ open: false }">
    {{-- Side decorations --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'rose-01', 'class' => 'left-2 top-10 opacity-20'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'leaf-01', 'class' => 'right-2 bottom-10 opacity-15'])

    <div class="max-w-4xl mx-auto relative z-10">
        {{-- Section Header --}}
        <div class="section-header fade-up" data-animation>
            {{-- Gift Icon --}}
            <div class="gift-icon-float float mb-4 mx-auto w-16 h-16">
                <img src="{{ themeAsset('icon.gift') }}" alt="Gift" class="w-full h-full object-contain" />
            </div>

            <h2 class="section-title" style="font-family: var(--theme-font-heading);">Kado Digital</h2>
            <div class="section-line"></div>
            <p class="section-desc mt-3">
                Doa restu Anda merupakan karunia terindah bagi kami. Namun jika Anda ingin memberikan tanda kasih, Anda dapat mengirimkannya secara digital.
            </p>
        </div>

        {{-- Toggle Button --}}
        <div class="fade-up mt-4" data-animation style="animation-delay: 0.15s;">
            <button @click="open = !open" class="gift-toggle-btn">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                <span x-text="open ? 'Tutup' : 'Kirim Kado Digital'"></span>
            </button>
        </div>

        {{-- Gift Cards --}}
        <div 
            x-show="open" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-3"
            class="space-y-3 max-w-xs mx-auto pt-4"
        >
            @if(isset($themeConfig['gifts']) && is_array($themeConfig['gifts']))
                @foreach($themeConfig['gifts'] as $giftIndex => $gift)
                    <div class="gift-card" style="animation-delay: {{ $giftIndex * 0.1 }}s;">
                        <span class="gift-bank-name">{{ $gift['bank_name'] }}</span>
                        <p class="gift-account-number">{{ $gift['account_number'] }}</p>
                        <p class="gift-owner">a.n. {{ $gift['owner'] }}</p>
                        
                        {{-- Copy Button --}}
                        <button 
                            @click="navigator.clipboard.writeText('{{ $gift['account_number'] }}').then(() => { $el.textContent = 'Tersalin ✓'; setTimeout(() => $el.textContent = 'Salin Nomor', 2000); })"
                            class="mt-2 text-xs font-semibold py-1.5 px-4 rounded-full cursor-pointer transition"
                            style="color: var(--theme-primary); border: 1px solid var(--theme-secondary);"
                        >
                            Salin Nomor
                        </button>
                    </div>
                @endforeach
            @else
                <div class="gift-card">
                    <span class="gift-bank-name">Bank Transfer</span>
                    <p class="gift-account-number">123-456-789</p>
                    <p class="gift-owner">a.n. Mempelai</p>
                </div>
            @endif
        </div>
    </div>
</section>