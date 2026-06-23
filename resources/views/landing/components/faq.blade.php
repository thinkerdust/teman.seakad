<section id="faq" class="py-24 px-6 md:px-12 bg-white dark:bg-zinc-950 relative">
    <div class="max-w-4xl mx-auto">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-600 dark:text-rose-gold-400 bg-rose-gold-50 dark:bg-rose-gold-950/20 px-3 py-1.5 rounded-full">
                Tanya Jawab (FAQ)
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Pertanyaan yang Sering Diajukan
            </h2>
            <div class="h-0.5 w-16 bg-rose-gold-400 mx-auto"></div>
            <p class="text-zinc-650 dark:text-zinc-400 text-sm">
                Masih memiliki pertanyaan? Temukan jawabannya di bawah ini atau hubungi tim bantuan kami.
            </p>
        </div>

        <!-- FAQ Accordion List with Alpine.js -->
        <div x-data="{ activeAccordion: null }" class="space-y-4">
            <!-- FAQ 1 -->
            <div class="border border-rose-gold-100/50 rounded-2xl bg-[#faf8f5]/40 dark:border-zinc-800 dark:bg-zinc-900/30 overflow-hidden">
                <button 
                    x-on:click="activeAccordion = (activeAccordion === 1 ? null : 1)" 
                    class="w-full p-6 text-left flex items-center justify-between gap-4 font-serif text-base font-bold text-zinc-800 dark:text-zinc-200 focus:outline-none"
                >
                    <span>Apakah bisa custom tema?</span>
                    <svg 
                        :class="activeAccordion === 1 ? 'rotate-180 text-rose-gold-500' : 'text-zinc-400'"
                        class="h-5 w-5 transition-transform duration-300" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div 
                    x-show="activeAccordion === 1" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-40"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 max-h-40"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed border-t border-rose-gold-100/20 dark:border-zinc-800/40 pt-4"
                >
                    Tidak, kami tidak melayani pembuatan tema kustom secara mandiri oleh user. Namun, kami telah menyediakan berbagai pilihan tema premium yang siap pakai dan bisa Anda sesuaikan langsung informasinya.
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="border border-rose-gold-100/50 rounded-2xl bg-[#faf8f5]/40 dark:border-zinc-800 dark:bg-zinc-900/30 overflow-hidden">
                <button 
                    x-on:click="activeAccordion = (activeAccordion === 2 ? null : 2)" 
                    class="w-full p-6 text-left flex items-center justify-between gap-4 font-serif text-base font-bold text-zinc-800 dark:text-zinc-200 focus:outline-none"
                >
                    <span>Apakah tamu bisa RSVP?</span>
                    <svg 
                        :class="activeAccordion === 2 ? 'rotate-180 text-rose-gold-500' : 'text-zinc-400'"
                        class="h-5 w-5 transition-transform duration-300" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div 
                    x-show="activeAccordion === 2" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-40"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 max-h-40"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed border-t border-rose-gold-100/20 dark:border-zinc-800/40 pt-4"
                >
                    Ya, tentu saja bisa. Tamu undangan dapat melakukan konfirmasi kehadiran (RSVP) secara langsung melalui halaman undangan digital Anda, dan Anda dapat memantau daftarnya di dashboard admin.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="border border-rose-gold-100/50 rounded-2xl bg-[#faf8f5]/40 dark:border-zinc-800 dark:bg-zinc-900/30 overflow-hidden">
                <button 
                    x-on:click="activeAccordion = (activeAccordion === 3 ? null : 3)" 
                    class="w-full p-6 text-left flex items-center justify-between gap-4 font-serif text-base font-bold text-zinc-800 dark:text-zinc-200 focus:outline-none"
                >
                    <span>Apakah bisa upload foto?</span>
                    <svg 
                        :class="activeAccordion === 3 ? 'rotate-180 text-rose-gold-500' : 'text-zinc-400'"
                        class="h-5 w-5 transition-transform duration-300" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div 
                    x-show="activeAccordion === 3" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-40"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 max-h-40"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed border-t border-rose-gold-100/20 dark:border-zinc-800/40 pt-4"
                >
                    Ya, bisa. Anda dapat mengunggah foto-foto pre-wedding terbaik Anda ke dalam galeri undangan digital Anda langsung dari panel admin.
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="border border-rose-gold-100/50 rounded-2xl bg-[#faf8f5]/40 dark:border-zinc-800 dark:bg-zinc-900/30 overflow-hidden">
                <button 
                    x-on:click="activeAccordion = (activeAccordion === 4 ? null : 4)" 
                    class="w-full p-6 text-left flex items-center justify-between gap-4 font-serif text-base font-bold text-zinc-800 dark:text-zinc-200 focus:outline-none"
                >
                    <span>Apakah bisa menggunakan domain sendiri?</span>
                    <svg 
                        :class="activeAccordion === 4 ? 'rotate-180 text-rose-gold-500' : 'text-zinc-400'"
                        class="h-5 w-5 transition-transform duration-300" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div 
                    x-show="activeAccordion === 4" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-40"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 max-h-40"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed border-t border-rose-gold-100/20 dark:border-zinc-800/40 pt-4"
                >
                    Tidak, untuk saat ini alamat undangan akan menggunakan subdomain dari platform kami (contoh: teman-seakad.com/nama-undangan). Hal ini memastikan performa loading undangan tetap cepat dan terkelola dengan baik.
                </div>
            </div>
        </div>
    </div>
</section>
