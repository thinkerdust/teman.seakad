<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    event_date: {
        type: String,
        required: true
    }
})

const days = ref(0)
const hours = ref(0)
const minutes = ref(0)
const seconds = ref(0)
const isFinished = ref(false)
let timerId = null

const calculateTime = () => {
    if (!props.event_date) return
    
    // Replace hyphens with slashes for iOS/Safari Date parsing stability if format is YYYY-MM-DD HH:MM:SS
    const targetString = props.event_date.replace(/-/g, '/')
    const target = new Date(targetString).getTime()
    const now = new Date().getTime()
    const difference = target - now

    if (isNaN(target) || difference <= 0) {
        isFinished.value = true
        days.value = 0
        hours.value = 0
        minutes.value = 0
        seconds.value = 0
        if (timerId) {
            clearInterval(timerId)
        }
        return
    }

    days.value = Math.floor(difference / (1000 * 60 * 60 * 24))
    hours.value = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
    minutes.value = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60))
    seconds.value = Math.floor((difference % (1000 * 60)) / 1000)
}

onMounted(() => {
    calculateTime()
    timerId = setInterval(calculateTime, 1000)
})

onUnmounted(() => {
    if (timerId) {
        clearInterval(timerId)
    }
})
</script>

<template>
    <div class="countdown-section py-12 px-4 text-center">
        <div class="max-w-xl mx-auto">
            <h3 class="countdown-title text-sm sm:text-base uppercase tracking-widest font-semibold mb-6">
                Menuju Hari Bahagia
            </h3>
            
            <div v-if="!isFinished" class="grid grid-cols-4 gap-2 sm:gap-4 max-w-sm sm:max-w-md mx-auto">
                <!-- Hari -->
                <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                    <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight">{{ days }}</span>
                    <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Hari</span>
                </div>
                
                <!-- Jam -->
                <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                    <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight">{{ hours }}</span>
                    <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Jam</span>
                </div>
                
                <!-- Menit -->
                <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                    <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight">{{ minutes }}</span>
                    <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Menit</span>
                </div>
                
                <!-- Detik -->
                <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                    <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight">{{ seconds }}</span>
                    <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Detik</span>
                </div>
            </div>
            
            <div v-else class="countdown-finished py-4">
                <h4 class="text-xl sm:text-2xl font-bold countdown-finished-title">
                    Hari Bahagia Telah Tiba!
                </h4>
                <p class="text-xs sm:text-sm opacity-75 mt-2">
                    Mohon do'a restu agar menjadi keluarga yang Sakinah, Mawaddah, warahmah.
                </p>
            </div>
        </div>
    </div>
</template>
