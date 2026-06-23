<section class="py-24 px-6 md:px-12 bg-white dark:bg-zinc-950 relative overflow-hidden">
    <!-- Ambient glows -->
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-champagne-100/20 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-600 dark:text-rose-gold-400 bg-rose-gold-50 dark:bg-rose-gold-950/20 px-3 py-1.5 rounded-full">
                Testimoni Bahagia
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Kisah Indah dari Mereka yang Menggunakan Layanan Kami
            </h2>
            <div class="h-0.5 w-16 bg-rose-gold-400 mx-auto"></div>
            <p class="text-zinc-650 dark:text-zinc-400">
                Kepuasan pelanggan adalah prioritas kami. Simak cerita bahagia dari para pasangan pengantin baru yang telah mempercayakan undangan pernikahan digital mereka kepada Teman Seakad.
            </p>
        </div>

        <!-- Testimonial Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Review 1 -->
            <div class="bg-[#faf8f5]/40 border border-rose-gold-100/40 p-8 rounded-2xl flex flex-col justify-between hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/30 dark:hover:bg-zinc-900 hover:shadow-xl hover:shadow-rose-gold-500/5 transition duration-300">
                <div class="space-y-4">
                    <!-- Rating -->
                    <div class="flex text-champagne-500">
                        @for($i=0; $i<5; $i++)
                            <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs leading-relaxed text-zinc-650 dark:text-zinc-400 italic">
                        "Desain undangan di Teman Seakad sangat mewah dan elegan! Banyak tamu undangan yang memuji keindahan website pernikahan kami. Fitur RSVP juga sangat membantu memantau katering."
                    </p>
                </div>
                <div class="flex items-center gap-4 mt-6 pt-4 border-t border-rose-gold-100/30 dark:border-zinc-800">
                    <img class="h-10 w-10 rounded-full object-cover border border-rose-gold-200" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=150" alt="Avatar Aditya & Rika" loading="lazy">
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white">Rika & Aditya</h4>
                        <span class="text-[10px] text-zinc-400 uppercase tracking-wider font-semibold">Menikah Maret 2026</span>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-[#faf8f5]/40 border border-rose-gold-100/40 p-8 rounded-2xl flex flex-col justify-between hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/30 dark:hover:bg-zinc-900 hover:shadow-xl hover:shadow-rose-gold-500/5 transition duration-300">
                <div class="space-y-4">
                    <div class="flex text-champagne-500">
                        @for($i=0; $i<5; $i++)
                            <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs leading-relaxed text-zinc-650 dark:text-zinc-400 italic">
                        "Sangat mudah digunakan bahkan untuk kami yang gaptek. Proses edit detail mempelai dan upload galeri foto berlangsung instan. Musik latar belakangnya juga bikin undangan makin syahdu."
                    </p>
                </div>
                <div class="flex items-center gap-4 mt-6 pt-4 border-t border-rose-gold-100/30 dark:border-zinc-800">
                    <img class="h-10 w-10 rounded-full object-cover border border-rose-gold-200" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150" alt="Avatar Budi & Laras" loading="lazy">
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white">Laras & Budi</h4>
                        <span class="text-[10px] text-zinc-400 uppercase tracking-wider font-semibold">Menikah Mei 2026</span>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-[#faf8f5]/40 border border-rose-gold-100/40 p-8 rounded-2xl flex flex-col justify-between hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/30 dark:hover:bg-zinc-900 hover:shadow-xl hover:shadow-rose-gold-500/5 transition duration-300">
                <div class="space-y-4">
                    <div class="flex text-champagne-500">
                        @for($i=0; $i<5; $i++)
                            <svg class="h-4.5 w-4.5 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs leading-relaxed text-zinc-650 dark:text-zinc-400 italic">
                        "Layanan customer support-nya luar biasa ramah dan solutif. Undangan bisa langsung diakses dengan lancar tanpa buffering. Pilihan lagunya banyak dan galeri fotonya rapi sekali."
                    </p>
                </div>
                <div class="flex items-center gap-4 mt-6 pt-4 border-t border-rose-gold-100/30 dark:border-zinc-800">
                    <img class="h-10 w-10 rounded-full object-cover border border-rose-gold-200" src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&q=80&w=150" alt="Avatar Rian & Fitri" loading="lazy">
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white">Fitri & Rian</h4>
                        <span class="text-[10px] text-zinc-400 uppercase tracking-wider font-semibold">Menikah Juni 2026</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
