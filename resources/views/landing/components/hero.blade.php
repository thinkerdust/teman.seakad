@php
    $groom = $featuredInvitation ? $featuredInvitation->groom_name : 'Yusuf';
    $bride = $featuredInvitation ? $featuredInvitation->bride_name : 'Anisa';
    
    // Format tanggal akad pernikahan jika tersedia
    if ($featuredInvitation && $featuredInvitation->akad_date) {
        try {
            $date = \Carbon\Carbon::parse($featuredInvitation->akad_date)->translatedFormat('l, d F Y');
        } catch (\Exception $e) {
            $date = 'Minggu, 12 Desember 2026';
        }
    } else {
        $date = 'Minggu, 12 Desember 2026';
    }

    // Ambil gambar pertama dari galeri jika ada, jika tidak gunakan fallback Unsplash
    $bgImage = 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80&w=800';
    if ($featuredInvitation && $featuredInvitation->galleries && $featuredInvitation->galleries->count() > 0) {
        $firstGallery = $featuredInvitation->galleries->first();
        if ($firstGallery && !empty($firstGallery->path)) {
            $bgImage = '/storage/' . $firstGallery->path;
        }
    }
@endphp

<section class="relative min-h-[95vh] flex items-center justify-center overflow-hidden py-24 px-6 md:px-12 bg-gradient-to-b from-rose-gold-50 via-white to-white dark:from-zinc-950 dark:via-zinc-950 dark:to-zinc-900">
    <!-- Particle Canvas -->
    <canvas id="particle-canvas" class="particle-container"></canvas>

    <!-- Decorative Ambient Lights/Gradients -->
    <div class="absolute top-1/4 left-1/10 w-96 h-96 bg-rose-gold-200/35 rounded-full filter blur-3xl dark:bg-rose-gold-950/20 hero-bg-gradient pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/10 w-96 h-96 bg-champagne-200/35 rounded-full filter blur-3xl dark:bg-champagne-950/20 hero-bg-gradient pointer-events-none" style="animation-delay: -3s;"></div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-16 items-center relative z-10 w-full">
        <!-- Hero Text Content -->
        <div class="lg:col-span-7 text-center lg:text-left space-y-8">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-gold-50 border border-rose-gold-100 dark:bg-rose-gold-950/20 dark:border-rose-gold-900/30 float-element">
                <span class="flex h-2 w-2 rounded-full bg-rose-gold-500 animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-700 dark:text-rose-gold-400">
                    Platform Undangan Digital Premium
                </span>
            </div>

            <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white leading-[1.15] hero-title">
                Create Your Dream <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-gold-500 to-champagne-600 dark:from-rose-gold-400 dark:to-champagne-400">
                    Wedding Invitation
                </span>
            </h1>

            <p class="text-lg md:text-xl text-zinc-600 dark:text-zinc-400 max-w-xl mx-auto lg:mx-0 leading-relaxed hero-subtitle">
                Buat undangan pernikahan digital yang elegan, mewah, interaktif, dan mudah dibagikan. Abadikan momen sakral Anda dalam sentuhan modern yang premium.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 hero-cta">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-full text-base font-bold text-center bg-gradient-to-r from-rose-gold-500 to-rose-gold-600 text-white shadow-xl shadow-rose-gold-500/20 hover:shadow-2xl hover:shadow-rose-gold-500/35 hover:scale-[1.03] transition-all duration-300">
                    Buat Undangan Sekarang
                </a>
                <a href="#themes" class="w-full sm:w-auto px-8 py-4 rounded-full text-base font-bold text-center border-2 border-rose-gold-200 text-rose-gold-600 hover:bg-rose-gold-50 dark:border-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-900 transition-all duration-300">
                    Eksplorasi Tema
                </a>
            </div>

            <!-- Client Stats / Social proof -->
            <div class="pt-8 border-t border-rose-gold-100/50 dark:border-zinc-800 grid grid-cols-3 gap-6 max-w-md mx-auto lg:mx-0 hero-stats">
                <div>
                    <div class="text-3xl font-serif font-extrabold text-rose-gold-600 dark:text-rose-gold-400">10k+</div>
                    <div class="text-[10px] text-zinc-500 dark:text-zinc-500 uppercase tracking-wider font-bold mt-1">Undangan Dibuat</div>
                </div>
                <div>
                    <div class="text-3xl font-serif font-extrabold text-rose-gold-600 dark:text-rose-gold-400">99.8%</div>
                    <div class="text-[10px] text-zinc-500 dark:text-zinc-500 uppercase tracking-wider font-bold mt-1">Kepuasan Pengguna</div>
                </div>
                <div>
                    <div class="text-3xl font-serif font-extrabold text-rose-gold-600 dark:text-rose-gold-400">24/7</div>
                    <div class="text-[10px] text-zinc-500 dark:text-zinc-500 uppercase tracking-wider font-bold mt-1">Dukungan Aktif</div>
                </div>
            </div>
        </div>

        <!-- Hero Mockup/Visual Content (3D tilt follow) -->
        <div class="lg:col-span-5 relative flex items-center justify-center hero-phone-container">
            <div class="hero-phone-mockup relative w-full max-w-[320px] aspect-[9/18] rounded-[48px] border-[12px] border-zinc-950 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-2xl overflow-hidden">
                <!-- Speaker / Camera Notch -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-28 h-5 bg-zinc-950 dark:bg-zinc-800 rounded-b-2xl z-20 flex items-center justify-center">
                    <span class="w-10 h-0.5 bg-zinc-800 dark:bg-zinc-700 rounded-full"></span>
                </div>
                
                <!-- Mock Invitation Content -->
                <div class="h-full w-full bg-cover bg-center relative" style="background-image: url('{{ $bgImage }}');">
                    <!-- Dark overlay -->
                    <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/25 to-black/75 flex flex-col justify-between p-6 pt-12 text-white text-center">
                        <div class="space-y-1">
                            <span class="text-[9px] uppercase tracking-widest text-champagne-300 font-bold">The Wedding of</span>
                            <div class="h-[1px] w-6 bg-champagne-300 mx-auto"></div>
                        </div>

                        <div class="space-y-2">
                            <h2 class="font-serif text-3xl font-bold tracking-wide text-transparent bg-clip-text bg-gradient-to-b from-champagne-100 to-champagne-300">
                                {{ $groom }} & {{ $bride }}
                            </h2>
                            <p class="text-[10px] text-zinc-350">{{ $date }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="py-2 px-3 rounded-lg bg-white/10 backdrop-blur-md border border-white/15">
                                <span class="block text-[8px] uppercase tracking-widest text-zinc-350">Kepada Yth.</span>
                                <span class="text-xs font-bold text-champagne-100 mt-0.5 block">Tamu Undangan</span>
                            </div>
                            <button class="w-full py-2.5 rounded-lg bg-gradient-to-r from-rose-gold-500 to-rose-gold-650 text-white text-[11px] font-extrabold shadow-md tracking-wider uppercase">
                                Buka Undangan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Decorative Rings Behind/Beside Mockup -->
            <div class="absolute -right-8 top-12 -z-10 w-24 h-24 border-2 border-champagne-400/40 rounded-full float-element"></div>
            <div class="absolute -left-10 bottom-12 -z-10 w-32 h-32 border-2 border-rose-gold-300/30 rounded-full float-element"></div>
        </div>
    </div>
</section>
