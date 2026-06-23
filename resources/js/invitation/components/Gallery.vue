<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    gallery: {
        type: Array,
        default: () => []
    }
})

const activeIndex = ref(null)

const isOpen = computed(() => activeIndex.value !== null)

const activeImage = computed(() => {
    if (activeIndex.value === null) return null
    return props.gallery[activeIndex.value]
})

const openLightbox = (index) => {
    activeIndex.value = index
    document.body.classList.add('overflow-hidden')
}

const closeLightbox = () => {
    activeIndex.value = null
    document.body.classList.remove('overflow-hidden')
}

const nextImage = () => {
    if (activeIndex.value === null) return
    activeIndex.value = (activeIndex.value + 1) % props.gallery.length
}

const prevImage = () => {
    if (activeIndex.value === null) return
    activeIndex.value = (activeIndex.value - 1 + props.gallery.length) % props.gallery.length
}

// Touch swipe navigation for mobile
const touchStartX = ref(0)
const touchEndX = ref(0)

const handleTouchStart = (e) => {
    touchStartX.value = e.changedTouches[0].screenX
}

const handleTouchEnd = (e) => {
    touchEndX.value = e.changedTouches[0].screenX
    handleSwipe()
}

const handleSwipe = () => {
    const swipeThreshold = 50
    if (touchEndX.value < touchStartX.value - swipeThreshold) {
        nextImage()
    } else if (touchEndX.value > touchStartX.value + swipeThreshold) {
        prevImage()
    }
}
</script>

<template>
    <div class="gallery-section py-12 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <span class="gallery-subtitle text-xs uppercase tracking-widest font-semibold">Galeri</span>
                <h3 class="gallery-title text-2xl sm:text-3xl font-bold mt-1">Momen Bahagia Kami</h3>
                <p class="gallery-desc text-xs sm:text-sm opacity-70 mt-2 max-w-md mx-auto">
                    Sekelumit kisah cinta kami yang terangkum dalam bingkai gambar indah
                </p>
            </div>

            <div v-if="gallery && gallery.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                <div 
                    v-for="(item, index) in gallery" 
                    :key="item.id || index"
                    @click="openLightbox(index)"
                    class="gallery-item-wrapper relative aspect-square overflow-hidden rounded-xl group cursor-pointer border border-stone-200/40 bg-stone-100 shadow-sm"
                >
                    <img 
                        :src="item.image" 
                        alt="Wedding gallery image"
                        loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white stroke-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div v-else class="py-8 text-stone-400 text-sm italic">
                Belum ada galeri foto yang diunggah.
            </div>
        </div>

        <!-- Lightbox Modal -->
        <Transition name="fade">
            <div 
                v-if="isOpen" 
                class="fixed inset-0 z-[99999] bg-black/95 flex flex-col justify-between p-4"
                @keydown.esc="closeLightbox"
                tabindex="0"
            >
                <!-- Top bar -->
                <div class="flex justify-between items-center text-white px-2 py-1">
                    <span class="text-xs font-semibold opacity-85">
                        {{ activeIndex + 1 }} / {{ gallery.length }}
                    </span>
                    <button 
                        @click="closeLightbox" 
                        class="text-white hover:text-stone-300 p-2 rounded-full hover:bg-white/10 transition duration-200 cursor-pointer"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Main Content (Image & Navigation) -->
                <div class="flex-grow flex items-center justify-between relative min-h-0">
                    <!-- Prev Button -->
                    <button 
                        @click="prevImage" 
                        class="absolute left-2 z-10 text-white hover:text-stone-300 p-3 rounded-full bg-black/40 hover:bg-black/60 transition duration-200 md:relative md:left-0 cursor-pointer"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Active Image -->
                    <div 
                        class="w-full max-w-4xl max-h-full flex items-center justify-center overflow-hidden mx-auto select-none"
                        @touchstart="handleTouchStart"
                        @touchend="handleTouchEnd"
                    >
                        <img 
                            :src="activeImage.image" 
                            class="max-w-full max-h-[80vh] object-contain rounded shadow-xl"
                            alt="Expanded view"
                        />
                    </div>

                    <!-- Next Button -->
                    <button 
                        @click="nextImage" 
                        class="absolute right-2 z-10 text-white hover:text-stone-300 p-3 rounded-full bg-black/40 hover:bg-black/60 transition duration-200 md:relative md:right-0 cursor-pointer"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Bottom bar -->
                <div class="py-2 text-center text-stone-400 text-xs select-none">
                    Gunakan swipe atau tombol panah untuk navigasi
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
