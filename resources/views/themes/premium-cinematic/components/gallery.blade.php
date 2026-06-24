<section 
    class="py-16 px-6 border-b border-neutral-850 space-y-12" 
    data-animation="fade-up"
    x-data="{ activeImage: null }"
>
    <div class="text-center space-y-2">
        <h2 class="font-heading text-2xl text-amber-300 font-bold uppercase tracking-widest">Galeri Foto</h2>
        <div class="h-[1px] w-12 bg-amber-400 mx-auto mt-2"></div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-2 gap-3">
        @foreach($invitationData['gallery'] as $photo)
            <div 
                class="aspect-square overflow-hidden rounded-xl bg-neutral-900 border border-neutral-800 cursor-pointer group"
                @click="activeImage = '{{ asset($photo['image']) }}'"
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
        class="fixed inset-0 z-99999 flex items-center justify-center bg-black/90 p-4"
        @click="activeImage = null"
    >
        <button class="absolute top-4 right-4 text-white hover:text-amber-200">
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
