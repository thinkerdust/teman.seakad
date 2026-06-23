<script setup>
import { onMounted, ref } from 'vue'
import Hero from '../../components/Hero.vue'
import Countdown from '../../components/Countdown.vue'
import Gallery from '../../components/Gallery.vue'
import Story from '../../components/Story.vue'
import Event from '../../components/Event.vue'
import './style.css'

const props = defineProps({
    groom_name: String,
    bride_name: String,
    event_date: String,
    venue: String,
    address: String,
    maps_url: String,
    description: String,
    gallery: Array,
    music: String,
    story: Array,
    events: Array
})

const isPlaying = ref(false)
const audioRef = ref(null)

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

onMounted(() => {
    // Hook into cover-overlay Buka Undangan button to trigger background music
    const btnOpen = document.getElementById('btn-open-invitation')
    if (btnOpen) {
        btnOpen.addEventListener('click', () => {
            setTimeout(() => {
                if (audioRef.value) {
                    audioRef.value.play().then(() => {
                        isPlaying.value = true
                    }).catch(err => {
                        console.log("Autoplay blocked:", err)
                    })
                }
            }, 500)
        })
    }
})
</script>

<template>
    <div class="theme-floral-elegant min-h-screen relative pb-12">
        <!-- Floating Music Controller -->
        <div v-if="music" class="fixed bottom-6 right-6 z-50">
            <audio ref="audioRef" :src="music" loop></audio>
            <button 
                @click="togglePlay"
                class="music-btn w-12 h-12 rounded-full flex items-center justify-center shadow-lg border cursor-pointer focus:outline-none transition-all duration-300"
                :class="isPlaying ? 'animate-spin-slow bg-rose-500 text-white border-rose-400' : 'bg-white text-stone-750 border-stone-200'"
            >
                <svg v-if="isPlaying" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
            </button>
        </div>

        <!-- Hero Section -->
        <Hero 
            :groom_name="groom_name" 
            :bride_name="bride_name" 
            :event_date="event_date" 
            :venue="venue" 
            :description="description" 
        />

        <!-- Elegant Separation Divider -->
        <div class="flex justify-center items-center py-6 floral-divider">
            <div class="h-[1px] w-24 bg-rose-250 opacity-40"></div>
            <span class="mx-4 text-rose-400 text-lg">❀</span>
            <div class="h-[1px] w-24 bg-rose-250 opacity-40"></div>
        </div>

        <!-- Countdown Section -->
        <Countdown :event_date="event_date" />

        <!-- Events Section -->
        <Event :events="events" :maps_url="maps_url" />

        <!-- Stories Section -->
        <Story :story="story" />

        <!-- Gallery Section -->
        <Gallery :gallery="gallery" />
    </div>
</template>
