{{-- 
    Gallery — Premium Album Experience (Phase 2.1)
    
    Features:
    - Masonry layout (CSS columns)
    - Combined photo frame system (arch, modern, round)
    - Paper texture + gallery-bg layers
    - Floral decoration overlays
    - Hover zoom & reveal animation
    - Fullscreen lightbox with Touch/swipe support
--}}

@php
    $themeFolder = $invitation->theme->folder;
    $shapes = ['arch', 'modern', 'round'];
@endphp

<section 
    class="py-12 px-4 text-center relative overflow-hidden"
    style="border-bottom: 1px solid var(--theme-secondary);"
    {!! themeAnimation('gallery') !!}
    x-data="{ activeImage: null, activeIndex: 0, images: {{ json_encode(collect($invitationData['gallery'])->pluck('image')->map(fn($img) => asset($img))->values()->all()) }} }"
>
    {{-- Layer 1: Gallery Background Image --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none" 
         style="background-image: url('{{ themeAsset('background.gallery') }}'); background-size: cover; background-position: center; z-index: 0;">
    </div>

    {{-- Layer 2: Paper Texture Overlay --}}
    <div class="absolute inset-0 opacity-15 pointer-events-none" 
         style="background-image: url('{{ themeAsset('background.paper') }}'); background-repeat: repeat; z-index: 1; mix-blend-mode: multiply;">
    </div>

    {{-- Layer 3: Floral Decorations --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'rose-01', 'class' => 'left-2 top-12 opacity-25'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'rose-02', 'class' => 'right-2 bottom-12 opacity-25'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'sparkle'])

    <div class="max-w-4xl mx-auto relative z-10">
        {{-- Section Header --}}
        <div class="section-header fade-up" data-animation>
            <span class="section-subtitle">Galeri</span>
            <h3 class="section-title">Momen Bahagia Kami</h3>
            <div class="section-line"></div>
            <p class="section-desc">
                Sekelumit kisah cinta kami yang terangkum dalam bingkai gambar indah
            </p>
        </div>

        @if(count($invitationData['gallery']) > 0)
            {{-- Masonry Gallery with custom photo frames --}}
            <div class="gallery-masonry">
                @foreach($invitationData['gallery'] as $photoIndex => $photo)
                    @php
                        $frameShape = $shapes[$photoIndex % 3];
                    @endphp
                    <div 
                        class="gallery-masonry-item fade-up"
                        data-animation
                        style="animation-delay: {{ ($photoIndex % 4) * 0.1 }}s;"
                        @click="activeImage = '{{ asset($photo['image']) }}'; activeIndex = {{ $photoIndex }};"
                    >
                        <div class="photo-frame {{ $frameShape }}">
                            {{-- Decorative Frame border --}}
                            <img src="{{ themeAsset('frame.' . $frameShape) }}" alt="" class="frame-border" aria-hidden="true" />
                            
                            {{-- User Photo --}}
                            <img 
                                src="{{ asset($photo['image']) }}" 
                                alt="Wedding gallery image {{ $photoIndex + 1 }}"
                                class="frame-photo"
                                loading="lazy"
                            />
                            
                            {{-- Hover Zoom/Search icon overlay --}}
                            <div class="gallery-overlay">
                                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 opacity-40 text-sm italic" style="color: var(--theme-text);">
                Belum ada galeri foto yang diunggah.
            </div>
        @endif
    </div>

    {{-- Fullscreen Lightbox --}}
    <div 
        x-show="activeImage" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[99999] flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.95);"
        @keydown.escape.window="activeImage = null"
        @keydown.left.window="activeIndex = (activeIndex - 1 + images.length) % images.length; activeImage = images[activeIndex];"
        @keydown.right.window="activeIndex = (activeIndex + 1) % images.length; activeImage = images[activeIndex];"
    >
        {{-- Close Button --}}
        <button @click="activeImage = null" class="absolute top-4 right-4 text-white/70 hover:text-white cursor-pointer z-10 transition">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Navigation: Previous --}}
        <button 
            @click.stop="activeIndex = (activeIndex - 1 + images.length) % images.length; activeImage = images[activeIndex];"
            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white/80 hover:bg-white/20 cursor-pointer transition z-10"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        {{-- Image --}}
        <img 
            :src="activeImage" 
            class="max-h-[85vh] max-w-[90vw] object-contain rounded shadow-2xl" 
            @click.stop
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        />

        {{-- Navigation: Next --}}
        <button 
            @click.stop="activeIndex = (activeIndex + 1) % images.length; activeImage = images[activeIndex];"
            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white/80 hover:bg-white/20 cursor-pointer transition z-10"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        {{-- Counter --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/50 text-xs tracking-widest">
            <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
        </div>

        {{-- Click backdrop to close --}}
        <div class="absolute inset-0 -z-10" @click="activeImage = null"></div>
    </div>

    {{-- Touch/Swipe support --}}
    <script>
        document.addEventListener('alpine:init', () => {
            // Simple swipe detection for lightbox
            let touchStartX = 0;
            document.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            document.addEventListener('touchend', (e) => {
                const touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 60) {
                    // Dispatch keyboard event equivalent for Alpine
                    window.dispatchEvent(new KeyboardEvent('keydown', { 
                        key: diff > 0 ? 'ArrowRight' : 'ArrowLeft' 
                    }));
                }
            }, { passive: true });
        });
    </script>
</section>