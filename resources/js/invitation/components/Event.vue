<script setup>
import { computed } from 'vue'

const props = defineProps({
    events: {
        type: Array,
        default: () => []
    },
    maps_url: {
        type: String,
        default: ''
    }
})

const formatEventDate = (dateStr) => {
    if (!dateStr) return ''
    try {
        const date = new Date(dateStr)
        if (isNaN(date.getTime())) return dateStr
        return new Intl.DateTimeFormat('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(date)
    } catch (e) {
        return dateStr
    }
}
</script>

<template>
    <div class="events-section py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <span class="events-subtitle text-xs uppercase tracking-widest font-semibold">Acara</span>
                <h3 class="events-title text-2xl sm:text-3xl font-bold mt-1">Detail Acara</h3>
                <p class="events-desc text-xs sm:text-sm opacity-70 mt-2 max-w-md mx-auto">
                    Detail pelaksanaan hari istimewa kami yang sangat kami harapkan kehadiran Anda
                </p>
            </div>

            <div v-if="events && events.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <div 
                    v-for="(event, index) in events" 
                    :key="event.id || index"
                    class="event-card bg-white p-6 sm:p-8 rounded-2xl border border-stone-200/80 shadow-sm flex flex-col justify-between relative overflow-hidden"
                >
                    <div class="relative z-10 space-y-6">
                        <div class="text-center">
                            <h4 class="event-name text-xl sm:text-2xl font-bold text-stone-850">
                                {{ event.name }}
                            </h4>
                            <div class="h-[1px] w-12 bg-amber-600/30 mx-auto my-3 event-card-divider"></div>
                        </div>

                        <div class="space-y-4">
                            <!-- Tanggal -->
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 p-1.5 rounded-lg bg-stone-100 text-amber-600 event-icon-bg">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <span class="block text-[9px] uppercase font-bold text-stone-400 tracking-wider">Tanggal</span>
                                    <span class="text-xs sm:text-sm font-semibold text-stone-750">{{ formatEventDate(event.date) }}</span>
                                </div>
                            </div>

                            <!-- Waktu -->
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 p-1.5 rounded-lg bg-stone-100 text-amber-600 event-icon-bg">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <span class="block text-[9px] uppercase font-bold text-stone-400 tracking-wider">Waktu</span>
                                    <span class="text-xs sm:text-sm font-semibold text-stone-750">{{ event.time }}</span>
                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 p-1.5 rounded-lg bg-stone-100 text-amber-600 event-icon-bg">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <span class="block text-[9px] uppercase font-bold text-stone-400 tracking-wider">Tempat</span>
                                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">{{ event.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="maps_url" class="mt-6 pt-4 border-t border-stone-100 event-card-footer">
                        <a 
                            :href="maps_url" 
                            target="_blank" 
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-stone-900 hover:bg-stone-850 text-white text-xs sm:text-sm font-semibold py-2.5 px-4 shadow transition duration-200 event-map-btn"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            Buka Peta Lokasi
                        </a>
                    </div>
                </div>
            </div>
            
            <div v-else class="text-center py-8 text-stone-400 text-sm italic">
                Belum ada rincian acara yang dibagikan.
            </div>
        </div>
    </div>
</template>
