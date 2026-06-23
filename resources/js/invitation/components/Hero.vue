<script setup>
import { onMounted, ref } from 'vue'
import { gsap } from 'gsap'

const props = defineProps({
    groom_name: {
        type: String,
        required: true
    },
    bride_name: {
        type: String,
        required: true
    },
    event_date: {
        type: String,
        default: ''
    },
    venue: {
        type: String,
        default: ''
    },
    description: {
        type: String,
        default: ''
    }
})

const titleRef = ref(null)
const detailsRef = ref(null)

onMounted(() => {
    // GSAP entry animation
    if (titleRef.value) {
        gsap.fromTo(titleRef.value, 
            { opacity: 0, y: 30 },
            { opacity: 1, y: 0, duration: 1.2, delay: 0.2, ease: 'power3.out' }
        )
    }
    if (detailsRef.value) {
        gsap.fromTo(detailsRef.value, 
            { opacity: 0, y: 20 },
            { opacity: 1, y: 0, duration: 1.2, delay: 0.6, ease: 'power3.out' }
        )
    }
})

const formatDate = (dateStr) => {
    if (!dateStr) return ''
    try {
        const date = new Date(dateStr)
        if (isNaN(date.getTime())) return dateStr
        return new Intl.DateTimeFormat('id-ID', {
            dateStyle: 'long'
        }).format(date)
    } catch (e) {
        return dateStr
    }
}
</script>

<template>
    <div class="hero-section relative flex flex-col justify-center items-center min-h-[90vh] py-16 px-6 text-center overflow-hidden">
        <!-- Background Overlay / Decorative elements container -->
        <div class="absolute inset-0 bg-cover bg-center opacity-10 pointer-events-none hero-bg"></div>
        
        <div class="relative z-10 max-w-2xl mx-auto flex flex-col items-center">
            <span class="hero-subtitle text-xs sm:text-sm uppercase tracking-[0.2em] font-semibold mb-6 inline-block">
                Undangan Pernikahan
            </span>
            
            <div ref="titleRef" class="hero-names-container my-4 sm:my-6 opacity-0">
                <h1 class="hero-names text-4xl sm:text-5xl md:text-7xl font-bold tracking-wide leading-tight">
                    {{ groom_name }} & {{ bride_name }}
                </h1>
            </div>
            
            <div ref="detailsRef" class="hero-details-container mt-6 space-y-4 opacity-0 w-full">
                <div class="h-[1px] w-24 bg-current opacity-30 mx-auto my-4 hero-divider"></div>
                <p class="hero-date text-lg sm:text-xl font-medium tracking-wide">
                    {{ formatDate(event_date) }}
                </p>
                <p class="hero-venue text-sm sm:text-base opacity-90 max-w-md mx-auto leading-relaxed">
                    {{ venue }}
                </p>
                <p v-if="description" class="hero-description text-xs sm:text-sm italic opacity-75 max-w-md mx-auto mt-8 px-4 leading-relaxed">
                    "{{ description }}"
                </p>
            </div>
        </div>
    </div>
</template>
