<section 
    class="py-16 px-6 border-b border-[var(--theme-secondary)]/30 space-y-12" 
    {!! themeAnimation('gallery') !!}
    x-data="{ activeImage: null }"
>
    <div class="text-center space-y-2">
        <h2 class="font-heading text-2xl text-[var(--theme-primary)] font-bold uppercase tracking-widest">Galeri Foto</h2>
        <div class="h-[1px] w-12 bg-[var(--theme-primary)] mx-auto mt-2"></div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-2 gap-3">
        @foreach($invitationData['gallery'] as $photo)
            <div 
                class="aspect-square overflow-hidden rounded-xl bg-[var(--theme-surface)] border border-[var(--theme-secondary)] cursor-pointer group"
                @click="activeImage = '{{ asset($photo['image']) }}'"
                data-gsap="fade-up"
            >
                <img 
                    src="{{ asset($photo['image']) }}" 
                    alt="Gallery Photo" 
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
            </div>
        @endforeach
    </div>

    <!-- Fullscreen Modal View -->
    <div 
        x-show="activeImage" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/90 p-4"
        @click="activeImage = null"
        @keydown.escape.window="activeImage = null"
    >
        <button class="absolute top-4 right-4 text-white hover:text-[var(--theme-primary)]">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img 
            :src="activeImage" 
            class="max-h-full max-w-full object-contain rounded-lg shadow-2xl" 
            @click.stop
        />
    </div>
</section>
