<section class="py-28 px-6 md:px-12 bg-white dark:bg-zinc-950 relative overflow-hidden reveal-section">
    <!-- Ambient glows -->
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-champagne-100/20 rounded-full blur-3xl dark:bg-champagne-950/10 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-600 dark:text-rose-gold-400 bg-rose-gold-50 dark:bg-rose-gold-950/20 px-3 py-1.5 rounded-full">
                Testimoni Bahagia
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                Kisah Indah Pasangan Teman Seakad
            </h2>
            <div class="h-0.5 w-16 bg-rose-gold-400 mx-auto"></div>
            <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                Simak cerita bahagia dari para pasangan pengantin baru yang telah mempercayakan undangan pernikahan digital mereka kepada kami.
            </p>
        </div>

        <!-- Alpine.js Carousel Container -->
        <div x-data="testimonialCarousel(3)" class="relative">
            <!-- Carousel Outer Wrap -->
            <div class="relative overflow-hidden min-h-[300px] md:min-h-[220px] flex items-center justify-center">
                
                <!-- Slide 1 -->
                <div 
                    x-show="active === 0"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-350 absolute"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full text-center space-y-6 px-4 md:px-12"
                >
                    <div class="flex justify-center text-champagne-500 gap-1">
                        @for($i=0; $i<5; $i++)
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="font-serif text-lg md:text-xl leading-relaxed text-zinc-700 dark:text-zinc-300 italic">
                        "Desain undangan di Teman Seakad sangat mewah dan elegan! Banyak tamu undangan yang memuji keindahan website pernikahan kami. Fitur RSVP juga sangat membantu memantau katering."
                    </p>
                    <div class="flex flex-col items-center gap-2">
                        <img class="h-12 w-12 rounded-full object-cover border-2 border-rose-gold-300" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=150" alt="Avatar Aditya & Rika" loading="lazy">
                        <div>
                            <h4 class="text-sm font-bold text-zinc-900 dark:text-white">Rika & Aditya</h4>
                            <span class="text-[10px] text-zinc-400 dark:text-zinc-500 uppercase tracking-widest font-bold">Menikah Maret 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div 
                    x-show="active === 1"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-350 absolute"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full text-center space-y-6 px-4 md:px-12"
                >
                    <div class="flex justify-center text-champagne-500 gap-1">
                        @for($i=0; $i<5; $i++)
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="font-serif text-lg md:text-xl leading-relaxed text-zinc-700 dark:text-zinc-300 italic">
                        "Sangat mudah digunakan bahkan untuk kami yang gaptek. Proses edit detail mempelai dan upload galeri foto berlangsung instan. Musik latar belakangnya juga bikin undangan makin syahdu."
                    </p>
                    <div class="flex flex-col items-center gap-2">
                        <img class="h-12 w-12 rounded-full object-cover border-2 border-rose-gold-300" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150" alt="Avatar Budi & Laras" loading="lazy">
                        <div>
                            <h4 class="text-sm font-bold text-zinc-900 dark:text-white">Laras & Budi</h4>
                            <span class="text-[10px] text-zinc-400 dark:text-zinc-500 uppercase tracking-widest font-bold">Menikah Mei 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div 
                    x-show="active === 2"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-350 absolute"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full text-center space-y-6 px-4 md:px-12"
                >
                    <div class="flex justify-center text-champagne-500 gap-1">
                        @for($i=0; $i<5; $i++)
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="font-serif text-lg md:text-xl leading-relaxed text-zinc-700 dark:text-zinc-300 italic">
                        "Layanan customer support-nya luar biasa ramah dan solutif. Undangan bisa langsung diakses dengan lancar tanpa buffering. Pilihan lagunya banyak dan galeri fotonya rapi sekali."
                    </p>
                    <div class="flex flex-col items-center gap-2">
                        <img class="h-12 w-12 rounded-full object-cover border-2 border-rose-gold-300" src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&q=80&w=150" alt="Avatar Rian & Fitri" loading="lazy">
                        <div>
                            <h4 class="text-sm font-bold text-zinc-900 dark:text-white">Fitri & Rian</h4>
                            <span class="text-[10px] text-zinc-400 dark:text-zinc-500 uppercase tracking-widest font-bold">Menikah Juni 2026</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Navigation Controls (Prev/Next buttons) -->
            <button 
                x-on:click="prev()" 
                class="absolute left-0 top-1/2 -translate-y-1/2 h-10 w-10 md:h-12 md:w-12 rounded-full border border-rose-gold-100 bg-white/80 dark:border-zinc-800 dark:bg-zinc-900/80 hover:bg-rose-gold-50 dark:hover:bg-zinc-850 flex items-center justify-center shadow-md transition duration-300"
                aria-label="Previous slide"
            >
                <svg class="h-5 w-5 text-rose-gold-600 dark:text-rose-gold-450" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button 
                x-on:click="next()" 
                class="absolute right-0 top-1/2 -translate-y-1/2 h-10 w-10 md:h-12 md:w-12 rounded-full border border-rose-gold-100 bg-white/80 dark:border-zinc-800 dark:bg-zinc-900/80 hover:bg-rose-gold-50 dark:hover:bg-zinc-850 flex items-center justify-center shadow-md transition duration-300"
                aria-label="Next slide"
            >
                <svg class="h-5 w-5 text-rose-gold-600 dark:text-rose-gold-450" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Slide Indicators (Dots) -->
            <div class="flex justify-center items-center gap-2.5 mt-8">
                <template x-for="i in count" :key="i">
                    <button 
                        x-on:click="goTo(i - 1)" 
                        :class="active === (i - 1) ? 'w-8 bg-rose-gold-500' : 'w-2 bg-rose-gold-250 dark:bg-zinc-800 hover:bg-rose-gold-300'"
                        class="h-2 rounded-full transition-all duration-300"
                        aria-label="Go to slide"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</section>
