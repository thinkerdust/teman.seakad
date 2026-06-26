<section 
    class="gallery-section py-12 px-4 text-center border-b border-stone-200/50" 
    {!! themeAnimation('gallery') !!}
    x-data="{ activeImage: null }"
>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <span class="gallery-subtitle text-xs uppercase tracking-widest font-semibold">Galeri</span>
            <h3 class="gallery-title text-2xl sm:text-3xl font-bold mt-1">Momen Bahagia Kami</h3>
            <p class="gallery-desc text-xs sm:text-sm opacity-70 mt-2 max-w-md mx-auto">
                Sekelumit kisah cinta kami yang terangkum dalam bingkai gambar indah
            </p>
        </div>

        @if(count($invitationData['gallery']) > 0)
            <div class="grid grid-cols-2 gap-3 max-w-md mx-auto">
                @foreach($invitationData['gallery'] as $photo)
                    <div 
                        class="gallery-item-wrapper relative aspect-square overflow-hidden rounded-xl group cursor-pointer border border-[var(--theme-secondary)] bg-[var(--theme-surface)] shadow-sm"
                        @click="activeImage = '{{ asset($photo['image']) }}'"
                        data-gsap="fade-up"
                    >
                        <img 
                            src="{{ asset($photo['image']) }}" 
                            alt="Wedding gallery image"
                            loading="lazy"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                        <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white stroke-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-stone-400 text-sm italic">
                Belum ada galeri foto yang diunggah.
            </div>
        @endif
    </div>

    <!-- Fullscreen Lightbox Modal -->
    <div 
        x-show="activeImage" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-99999 flex items-center justify-center bg-black/95 p-4"
        @click="activeImage = null"
        @keydown.escape.window="activeImage = null"
    >
        <button class="absolute top-4 right-4 text-white hover:text-stone-300 cursor-pointer">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img 
            :src="activeImage" 
            class="max-h-[85vh] max-w-full object-contain rounded shadow-2xl" 
            @click.stop
        />
    </div>
</section>