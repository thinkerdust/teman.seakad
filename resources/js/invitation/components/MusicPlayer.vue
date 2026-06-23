<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'

const props = defineProps({
    music: [String, Object], // Can be string URL or object { title, artist, file }
    autoplay: {
        type: Boolean,
        default: true
    }
})

const audioRef = ref(null)
const isPlaying = ref(false)
const isHovered = ref(false)
const duration = ref(0)
const currentTime = ref(0)
const volume = ref(0.8)

// Parse music prop to ensure we have title, artist, and file
const musicInfo = computed(() => {
    if (!props.music) return null
    if (typeof props.music === 'string') {
        const filename = props.music.split('/').pop() || ''
        return {
            title: filename.replace(/\.[^/.]+$/, "").replace(/[_-]/g, " "),
            artist: 'Wedding Music',
            file: props.music
        }
    }
    return {
        title: props.music.title || 'Lagu Pernikahan',
        artist: props.music.artist || 'Artis',
        file: props.music.file || ''
    }
})

// Progress percentage for circular svg
const progressPercent = computed(() => {
    if (!duration.value) return 0
    return (currentTime.value / duration.value) * 100
})

// SVG stroke offset calculation
const strokeDashoffset = computed(() => {
    const radius = 22
    const circumference = 2 * Math.PI * radius
    return circumference - (progressPercent.value / 100) * circumference
})

const togglePlay = () => {
    if (!audioRef.value) return
    if (isPlaying.value) {
        audioRef.value.pause()
        isPlaying.value = false
    } else {
        audioRef.value.play().then(() => {
            isPlaying.value = true
        }).catch(err => {
            console.log("Audio play failed:", err)
        })
    }
}

const handleTimeUpdate = () => {
    if (audioRef.value) {
        currentTime.value = audioRef.value.currentTime
    }
}

const handleMetadataLoaded = () => {
    if (audioRef.value) {
        duration.value = audioRef.value.duration
    }
}

const handleVolumeChange = (e) => {
    const newVol = parseFloat(e.target.value)
    volume.value = newVol
    if (audioRef.value) {
        audioRef.value.volume = newVol
    }
}

// Hook into cover-overlay "Buka Undangan" button to start play (for autoplay bypass)
onMounted(() => {
    const btnOpen = document.getElementById('btn-open-invitation')
    if (btnOpen && props.autoplay) {
        btnOpen.addEventListener('click', () => {
            setTimeout(() => {
                if (audioRef.value) {
                    audioRef.value.play().then(() => {
                        isPlaying.value = true
                    }).catch(err => {
                        console.log("Autoplay blocked:", err)
                    })
                }
            }, 600)
        })
    }
})

onUnmounted(() => {
    if (audioRef.value) {
        audioRef.value.pause()
    }
})
</script>

<template>
    <div 
        v-if="musicInfo && musicInfo.file"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 transition-all duration-500 ease-out"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <audio 
            ref="audioRef" 
            :src="musicInfo.file" 
            loop 
            preload="metadata"
            @timeupdate="handleTimeUpdate"
            @loadedmetadata="handleMetadataLoaded"
        ></audio>

        <!-- Premium Metadata and Control Panel (Expands on Hover) -->
        <div 
            class="flex items-center gap-3 bg-white/95 dark:bg-stone-900/95 backdrop-blur border border-stone-200/60 dark:border-stone-800/60 rounded-full py-1.5 pl-4 pr-2 shadow-xl transition-all duration-300 origin-right"
            :class="isHovered ? 'scale-100 opacity-100 max-w-xs' : 'scale-90 opacity-0 pointer-events-none max-w-0 overflow-hidden'"
            style="width: 220px;"
        >
            <div class="flex-grow min-w-0">
                <h5 class="text-xxs font-bold text-stone-800 dark:text-stone-200 truncate leading-snug">{{ musicInfo.title }}</h5>
                <p class="text-[9px] text-stone-400 dark:text-stone-500 truncate leading-none mt-0.5">{{ musicInfo.artist }}</p>
            </div>
            
            <!-- Volume Control slider -->
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-stone-400 dark:text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
                <input 
                    type="range" 
                    min="0" 
                    max="1" 
                    step="0.05" 
                    :value="volume" 
                    @input="handleVolumeChange"
                    class="w-12 h-1 bg-stone-200 dark:bg-stone-700 rounded-lg appearance-none cursor-pointer accent-rose-500"
                />
            </div>
        </div>

        <!-- Floating Action Button (Round) -->
        <button 
            @click="togglePlay"
            class="relative w-12 h-12 rounded-full flex items-center justify-center shadow-lg border cursor-pointer focus:outline-none transition-all duration-300"
            :class="isPlaying ? 'bg-rose-500 text-white border-rose-400' : 'bg-white text-stone-750 border-stone-200 dark:bg-stone-900 dark:text-stone-300 dark:border-stone-800'"
        >
            <!-- SVG Circular Progress Ring -->
            <svg class="absolute inset-0 w-full h-full transform -rotate-90 pointer-events-none">
                <circle 
                    cx="24" 
                    cy="24" 
                    r="22" 
                    class="stroke-current text-black/5 dark:text-white/5" 
                    stroke-width="2" 
                    fill="transparent"
                />
                <circle 
                    cx="24" 
                    cy="24" 
                    r="22" 
                    class="stroke-current transition-all duration-100" 
                    :class="isPlaying ? 'text-white/40' : 'text-rose-500/70'" 
                    stroke-width="2" 
                    fill="transparent"
                    :stroke-dasharray="2 * Math.PI * 22"
                    :stroke-dashoffset="strokeDashoffset"
                />
            </svg>

            <!-- Animated Rotating Music Icon -->
            <span :class="{ 'animate-spin-slow': isPlaying }" class="flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
            </span>
        </button>
    </div>
</template>

<style scoped>
.animate-spin-slow {
    animation: spin 8s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
