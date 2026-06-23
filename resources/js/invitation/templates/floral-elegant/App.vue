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
    <div class="theme-floral-elegant min-h-screen relative pb-12">
        <!-- Floating Music Controller -->
        <MusicPlayer :music="music" />

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
