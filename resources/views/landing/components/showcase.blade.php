<section id="themes" class="py-28 px-6 md:px-12 bg-[#faf8f5]/40 dark:bg-zinc-900/20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-20 space-y-4 reveal-section">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-600 dark:text-rose-gold-400 bg-rose-gold-50 dark:bg-rose-gold-950/20 px-3 py-1.5 rounded-full">
                Desain Tema Premium
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                Pilih Desain Tema yang Cocok dengan Gaya Anda
            </h2>
            <div class="h-0.5 w-16 bg-rose-gold-400 mx-auto"></div>
            <p class="text-zinc-600 dark:text-zinc-400">
                Kami menyediakan berbagai pilihan tema dengan estetika visual kelas atas, mulai dari nuansa bunga romantis hingga kemewahan modern.
            </p>
        </div>

        @if(isset($themes) && $themes->count() > 0)
            <div class="showcase-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach($themes as $index => $theme)
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
                        
                        // Berikan rotasi kecil yang bervariasi untuk efek stack yang artistik
                        $rotations = ['-rotate-1', 'rotate-1', '-rotate-2', 'rotate-2'];
                        $rotation = $rotations[$index % count($rotations)];
                    @endphp
                    
                    <!-- Showcase Card -->
                    <div class="showcase-card group bg-white dark:bg-zinc-900 p-4 rounded-3xl border border-rose-gold-100/40 dark:border-zinc-800 shadow-lg relative {{ $rotation }} hover:rotate-0 hover:z-10">
                        <!-- Image wrapper with 3D shadow depth -->
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 shadow-md">
                            <img 
                                src="{{ asset($thumbnailUrl) }}" 
                                alt="{{ $theme->name }}" 
                                class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-700 ease-out"
                                loading="lazy"
                            >
                            
                            <!-- Premium Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 text-white">
                                <span class="text-[9px] uppercase tracking-widest text-champagne-300 font-bold">Teman Seakad Exclusive</span>
                                <h3 class="font-serif text-xl font-bold mt-1 text-transparent bg-clip-text bg-gradient-to-r from-champagne-100 to-white">
                                    Tema {{ $theme->name }}
                                </h3>
                                <p class="text-[10px] text-zinc-300 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $theme->description ?: 'Undangan digital premium dengan desain eksklusif, RSVP terintegrasi, dan navigasi lokasi.' }}
                                </p>
                                <div class="pt-4 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-rose-gold-300 uppercase tracking-wider">Mulai Membuat</span>
                                    <a href="{{ route('login') }}" class="px-4 py-2 bg-gradient-to-r from-rose-gold-400 to-rose-gold-550 hover:from-rose-gold-500 hover:to-rose-gold-650 text-white text-[10px] font-bold rounded-lg shadow-md transition duration-300">
                                        Gunakan Desain
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Info -->
                        <div class="mt-4 px-2 flex justify-between items-center">
                            <div>
                                <h4 class="font-serif text-base font-bold text-zinc-900 dark:text-white">
                                    {{ $theme->name }}
                                </h4>
                                <p class="text-[10px] text-zinc-500 uppercase tracking-widest mt-0.5">Template Undangan</p>
                            </div>
                            <span class="px-2.5 py-1 bg-champagne-50 dark:bg-champagne-950/20 text-champagne-750 dark:text-champagne-400 rounded-lg text-[9px] font-bold uppercase border border-champagne-100/50 dark:border-champagne-900/30">
                                Active
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Fallback empty state -->
            <div class="text-center p-12 bg-[#faf8f5] dark:bg-zinc-900/40 rounded-2xl border border-dashed border-rose-gold-200/55 dark:border-zinc-800 max-w-md mx-auto reveal-section">
                <svg class="mx-auto h-12 w-12 text-rose-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-base font-bold text-zinc-900 dark:text-white">Belum Ada Galeri</h3>
                <p class="mt-2 text-xs text-zinc-500">Inspirasi tema undangan sedang dalam penyusunan oleh tim desainer kami.</p>
            </div>
        @endif
    </div>
</section>
