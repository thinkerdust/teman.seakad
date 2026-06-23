<script setup>
import { onMounted, ref } from 'vue'
import Hero from '../../components/Hero.vue'
import Countdown from '../../components/Countdown.vue'
import Gallery from '../../components/Gallery.vue'
import Story from '../../components/Story.vue'
import Event from '../../components/Event.vue'
import MusicPlayer from '../../components/MusicPlayer.vue'
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
    music: [String, Object],
    story: Array,
    events: Array
})
</script>

<template>
    <div class="theme-islamic-wedding min-h-screen relative pb-12">
        <!-- Floating Music Controller -->
        <MusicPlayer :music="music" />

        <!-- Bismillah Header -->
        <div class="pt-12 pb-2 text-center islamic-header">
            <span class="arabic-bismillah text-4xl block font-normal text-emerald-800 mb-3 select-none">
                بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
            </span>
            <p class="text-[10px] sm:text-xs uppercase tracking-widest text-emerald-600 font-semibold px-4 max-w-md mx-auto leading-relaxed">
                Dengan Menyebut Nama Allah Yang Maha Pengasih Lagi Maha Penyayang
            </p>
        </div>

        <!-- Hero Section -->
        <Hero 
            :groom_name="groom_name" 
            :bride_name="bride_name" 
            :event_date="event_date" 
            :venue="venue" 
            :description="description" 
        />

        <!-- Islamic Separation Divider -->
        <div class="flex justify-center items-center py-6 islamic-divider">
            <div class="h-[1px] w-24 bg-emerald-600/30"></div>
            <span class="mx-4 text-emerald-600 text-lg">❈</span>
            <div class="h-[1px] w-24 bg-emerald-600/30"></div>
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
