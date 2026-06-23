<section id="themes" class="py-24 px-6 md:px-12 bg-white dark:bg-zinc-950 relative">
    <div class="max-w-7xl mx-auto">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-600 dark:text-rose-gold-400 bg-rose-gold-50 dark:bg-rose-gold-950/20 px-3 py-1.5 rounded-full">
                Desain Tema Premium
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Pilih Desain Tema yang Cocok dengan Gaya Anda
            </h2>
            <div class="h-0.5 w-16 bg-rose-gold-400 mx-auto"></div>
            <p class="text-zinc-650 dark:text-zinc-400">
                Kami menyediakan berbagai pilihan tema dengan estetika visual kelas atas, mulai dari nuansa bunga romantis hingga kemewahan modern.
            </p>
        </div>

        <!-- Themes Grid -->
        @if(isset($themes) && $themes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($themes as $theme)
                    @php
                        $thumbnailUrl = $theme->thumbnail;
                        
                        // Gunakan file lokal dari public/assets/themes jika database kosong
                        if (empty($thumbnailUrl)) {
                            $slugKey = strtolower($theme->slug);
                            if (str_contains($slugKey, 'floral')) {
                                $thumbnailUrl = 'assets/themes/floral-elegant.jpg';
                            } elseif (str_contains($slugKey, 'luxury')) {
                                $thumbnailUrl = 'assets/themes/luxury-gold.jpg';
                            } elseif (str_contains($slugKey, 'islamic')) {
                                $thumbnailUrl = 'assets/themes/islamic-wedding.jpg';
                            } elseif (str_contains($slugKey, 'rustic')) {
                                $thumbnailUrl = 'assets/themes/rustic-forest.jpg';
                            } else {
                                $thumbnailUrl = 'assets/themes/floral-elegant.jpg';
                            }
                        }
                    @endphp
                    
                    <!-- Theme Card -->
                    <div class="group bg-[#faf8f5]/40 border border-rose-gold-100/50 rounded-2xl overflow-hidden hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/30 dark:hover:bg-zinc-900 hover:shadow-xl hover:shadow-rose-gold-500/5 hover:-translate-y-1.5 transition-all duration-300">
                        <!-- Image Container with Shimmer loading effect -->
                        <div class="relative aspect-[4/3] bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                            <img 
                                src="{{ asset($thumbnailUrl) }}" 
                                alt="Tema {{ $theme->name }}" 
                                loading="lazy"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500"
                            >
                            
                            <!-- Badges/Overlay -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-white/95 dark:bg-zinc-900/95 text-rose-gold-600 dark:text-rose-gold-400 shadow-sm">
                                    {{ $theme->name }}
                                </span>
                            </div>
                        </div>

                        <!-- Info Content -->
                        <div class="p-6 space-y-4">
                            <div class="space-y-2">
                                <h3 class="font-serif text-lg font-bold text-zinc-900 dark:text-white">
                                    Tema {{ $theme->name }}
                                </h3>
                                <p class="text-xs text-zinc-550 dark:text-zinc-400 leading-relaxed min-h-[36px]">
                                    {{ $theme->description ?: 'Pilihan desain tema undangan digital premium dengan tata letak modern dan elegan.' }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-rose-gold-100/30 dark:border-zinc-800">
                                <span class="text-xs font-semibold text-rose-gold-500 uppercase tracking-wider">Premium Template</span>
                                <a 
                                    href="{{ route('login') }}" 
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-zinc-800 dark:text-zinc-200 group-hover:text-rose-gold-600 transition-colors"
                                >
                                    Pilih Tema
                                    <svg class="h-3.5 w-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Fallback state if no active themes -->
            <div class="text-center p-12 bg-[#faf8f5] dark:bg-zinc-900/40 rounded-2xl border border-dashed border-rose-gold-200/55 dark:border-zinc-800 max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-rose-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-base font-bold text-zinc-900 dark:text-white">Belum Ada Tema</h3>
                <p class="mt-2 text-xs text-zinc-500">Tema undangan premium sedang dipersiapkan dan akan segera hadir dalam waktu dekat.</p>
            </div>
        @endif
    </div>
</section>
