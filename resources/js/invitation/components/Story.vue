<script setup>
import { onMounted, ref, computed } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const props = defineProps({
    story: {
        type: Array,
        default: () => []
    }
})

const sortedStory = computed(() => {
    return [...props.story].sort((a, b) => (a.sort || 0) - (b.sort || 0))
})

const storyItemsRef = ref([])

onMounted(() => {
    storyItemsRef.value.forEach((item, index) => {
        if (!item) return
        gsap.fromTo(item,
            { 
                opacity: 0, 
                y: 40,
                scale: 0.98
            },
            {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 1.0,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: item,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            }
        )
    })
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
    <div class="story-section py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <span class="story-subtitle text-xs uppercase tracking-widest font-semibold">Kisah Kami</span>
                <h3 class="story-title text-2xl sm:text-3xl font-bold mt-1">Perjalanan Cinta</h3>
                <p class="story-desc text-xs sm:text-sm opacity-70 mt-2 max-w-md mx-auto">
                    Bagaimana Tuhan mempertemukan kami dalam bingkai takdir yang indah
                </p>
            </div>

            <div v-if="sortedStory && sortedStory.length > 0" class="relative">
                <!-- Center timeline line -->
                <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-[2px] bg-stone-200/85 -translate-x-[1px] story-line"></div>
                
                <div class="space-y-12 relative">
                    <div 
                        v-for="(item, index) in sortedStory" 
                        :key="item.id || index"
                        :ref="el => storyItemsRef[index] = el"
                        class="flex flex-col md:flex-row items-stretch"
                        :class="index % 2 === 0 ? 'md:flex-row-reverse' : ''"
                    >
                        <!-- Spacer/Opposite card -->
                        <div class="hidden md:block w-1/2 px-8"></div>
                        
                        <!-- Timeline circle dot -->
                        <div class="absolute left-4 md:left-1/2 -translate-x-1/2 flex items-center justify-center z-10">
                            <div class="w-8 h-8 rounded-full bg-white border-2 border-amber-600 flex items-center justify-center shadow-sm story-dot">
                                <span class="text-amber-600 text-xs font-bold">{{ index + 1 }}</span>
                            </div>
                        </div>
                        
                        <!-- Story Card -->
                        <div class="w-full md:w-1/2 pl-12 md:pl-0 md:px-8">
                            <div class="story-card bg-white p-6 rounded-2xl border border-stone-200/80 shadow-sm relative hover:shadow-md transition duration-300">
                                <span class="story-card-date text-xs font-bold text-amber-600 tracking-wider">
                                    {{ formatDate(item.date) }}
                                </span>
                                <h4 class="story-card-title text-lg font-bold text-stone-850 mt-1 leading-snug">
                                    {{ item.title }}
                                </h4>
                                <p class="story-card-desc text-xs sm:text-sm text-stone-600 leading-relaxed mt-2 whitespace-pre-line">
                                    {{ item.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-else class="text-center py-8 text-stone-400 text-sm italic">
                Belum ada kisah perjalanan cinta yang dibagikan.
            </div>
        </div>
    </div>
</template>
