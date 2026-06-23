@php
    // Definisikan data default jika tidak ada featuredInvitation di database
    $hasInvitation = isset($featuredInvitation) && $featuredInvitation;
    $groom = $hasInvitation ? $featuredInvitation->groom_name : 'Yusuf';
    $bride = $hasInvitation ? $featuredInvitation->bride_name : 'Anisa';
    $eventDateObj = $hasInvitation ? ($featuredInvitation->reception_date ?? $featuredInvitation->akad_date) : \Carbon\Carbon::now()->addMonths(3);
    $eventDate = $eventDateObj ? $eventDateObj->toIso8601String() : '';
    $eventDateFormatted = $eventDateObj ? $eventDateObj->translatedFormat('d F Y') : '12 Desember 2026';
    $venue = $hasInvitation ? $featuredInvitation->venue : 'Gedung Serbaguna Seakad';
    $address = $hasInvitation ? $featuredInvitation->address : 'Jl. Akademik No. 12, Kota Seakad';
    $themeSlug = $hasInvitation && $featuredInvitation->theme ? $featuredInvitation->theme->slug : 'floral';
@endphp

<section id="preview" class="py-28 px-6 md:px-12 bg-[#faf8f5]/60 dark:bg-zinc-900/40 relative preview-section overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-20 space-y-4 reveal-section">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-600 dark:text-rose-gold-400 bg-rose-gold-50 dark:bg-rose-gold-950/20 px-3 py-1.5 rounded-full">
                Pratinjau Langsung
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                See Your Invitation Come Alive
            </h2>
            <div class="h-0.5 w-16 bg-rose-gold-400 mx-auto"></div>
            <p class="text-zinc-600 dark:text-zinc-400">
                Rasakan pengalaman interaktif pratinjau undangan digital sesungguhnya di dalam frame smartphone di bawah ini.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <!-- Left Information & Countdown -->
            <div class="lg:col-span-5 space-y-8 text-center lg:text-left reveal-section">
                <div class="space-y-4">
                    <h3 class="font-serif text-3xl font-extrabold text-zinc-900 dark:text-white">
                        {{ $groom }} & {{ $bride }}
                    </h3>
                    <p class="text-xs font-bold tracking-widest text-rose-gold-500 uppercase">
                        Akan Segera Menikah
                    </p>
                </div>

                <!-- Alpine.js Countdown Component -->
                <div 
                    x-data="{
                        countdown: { days: 0, hours: 0, minutes: 0, seconds: 0 },
                        eventDate: '{{ $eventDate }}',
                        init() {
                            if (!this.eventDate) return;
                            const target = new Date(this.eventDate).getTime();
                            const update = () => {
                                const now = new Date().getTime();
                                const distance = target - now;
                                if (distance < 0) {
                                    this.countdown = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                                    return;
                                }
                                this.countdown.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                this.countdown.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                this.countdown.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                this.countdown.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            };
                            update();
                            setInterval(update, 1000);
                        }
                    }"
                    class="grid grid-cols-4 gap-4 max-w-sm mx-auto lg:mx-0"
                >
                    <!-- Days -->
                    <div class="p-3 bg-white dark:bg-zinc-800 rounded-2xl border border-rose-gold-100/30 dark:border-zinc-700 shadow-sm text-center">
                        <span x-text="countdown.days" class="block font-serif text-2xl md:text-3xl font-extrabold text-rose-gold-600 dark:text-rose-gold-400">0</span>
                        <span class="text-[9px] uppercase text-zinc-400 tracking-wider font-bold">Hari</span>
                    </div>
                    <!-- Hours -->
                    <div class="p-3 bg-white dark:bg-zinc-800 rounded-2xl border border-rose-gold-100/30 dark:border-zinc-700 shadow-sm text-center">
                        <span x-text="countdown.hours" class="block font-serif text-2xl md:text-3xl font-extrabold text-rose-gold-600 dark:text-rose-gold-400">0</span>
                        <span class="text-[9px] uppercase text-zinc-400 tracking-wider font-bold">Jam</span>
                    </div>
                    <!-- Minutes -->
                    <div class="p-3 bg-white dark:bg-zinc-800 rounded-2xl border border-rose-gold-100/30 dark:border-zinc-700 shadow-sm text-center">
                        <span x-text="countdown.minutes" class="block font-serif text-2xl md:text-3xl font-extrabold text-rose-gold-600 dark:text-rose-gold-400">0</span>
                        <span class="text-[9px] uppercase text-zinc-400 tracking-wider font-bold">Menit</span>
                    </div>
                    <!-- Seconds -->
                    <div class="p-3 bg-white dark:bg-zinc-800 rounded-2xl border border-rose-gold-100/30 dark:border-zinc-700 shadow-sm text-center">
                        <span x-text="countdown.seconds" class="block font-serif text-2xl md:text-3xl font-extrabold text-rose-gold-600 dark:text-rose-gold-400">0</span>
                        <span class="text-[9px] uppercase text-zinc-400 tracking-wider font-bold">Detik</span>
                    </div>
                </div>

                <!-- Event details list -->
                <div class="space-y-4 text-sm text-zinc-600 dark:text-zinc-400 max-w-sm mx-auto lg:mx-0">
                    <div class="flex items-center gap-3 justify-center lg:justify-start">
                        <svg class="h-5 w-5 text-rose-gold-550 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">{{ $eventDateFormatted }}</span>
                    </div>
                    <div class="flex items-center gap-3 justify-center lg:justify-start">
                        <svg class="h-5 w-5 text-rose-gold-550 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-left leading-normal font-medium">{{ $venue }} - {{ $address }}</span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-rose-gold-500 hover:bg-rose-gold-650 text-white font-bold text-sm tracking-wide shadow-md shadow-rose-gold-500/15 hover:shadow-xl hover:shadow-rose-gold-500/25 transition-all duration-300">
                        Coba Tema Ini
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Smartphone Mockup View (Parallax screen content) -->
            <div class="lg:col-span-7 flex justify-center">
                <!-- Device shell -->
                <div class="preview-phone-mockup relative w-full max-w-[320px] aspect-[9/18.5] rounded-[44px] border-[12px] border-zinc-950 bg-white dark:border-zinc-800 dark:bg-zinc-900 shadow-2xl overflow-hidden">
                    <!-- Notch -->
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-28 h-5 bg-zinc-950 dark:bg-zinc-800 rounded-b-xl z-20"></div>

                    <!-- Sandbox Screen Box -->
                    <div class="h-full w-full overflow-hidden relative bg-[#fdfbf7]">
                        <!-- Absolute screen container animated by GSAP -->
                        <div class="preview-inner-screen w-full flex flex-col absolute top-0 left-0">
                            <!-- Cover Screen Hero inside the phone -->
                            <div class="h-[520px] relative flex flex-col justify-between items-center text-center p-6 py-14 text-[#2d2d2d] bg-no-repeat bg-cover bg-center shrink-0" style="background-image: linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.85)), url('https://images.unsplash.com/photo-1465408953385-7c4627c29435?auto=format&fit=crop&q=80&w=600');">
                                <div class="space-y-1">
                                    <span class="text-[9px] uppercase tracking-widest text-rose-gold-700 font-bold">Undangan Pernikahan</span>
                                    <div class="h-[1.5px] w-6 bg-rose-gold-400 mx-auto mt-1"></div>
                                </div>
                                
                                <div class="space-y-3">
                                    <h4 class="font-serif text-3xl font-extrabold tracking-wide text-rose-gold-800">
                                        {{ $groom }} & {{ $bride }}
                                    </h4>
                                    <div class="text-[10px] text-zinc-600 tracking-wider">
                                        {{ $eventDateFormatted }}
                                    </div>
                                </div>

                                <div class="space-y-3 w-full">
                                    <div class="py-2.5 px-3 rounded-xl bg-white/75 border border-rose-gold-100 shadow-sm text-center">
                                        <span class="block text-[8px] uppercase tracking-wider text-zinc-400">Kepada Yth.</span>
                                        <span class="text-[11px] font-bold text-zinc-700">Tamu Undangan</span>
                                    </div>
                                    <button class="w-full py-2.5 rounded-xl bg-rose-gold-500 text-white text-[11px] font-bold shadow-sm">
                                        Buka Undangan
                                    </button>
                                </div>
                            </div>

                            <!-- Inner Content Section inside the phone -->
                            <div class="p-6 text-center space-y-6 bg-white border-t border-rose-gold-100/30 pb-12">
                                <div class="space-y-2">
                                    <span class="font-serif text-lg font-bold text-zinc-800">Detail Acara</span>
                                    <p class="text-[10px] text-zinc-500 leading-relaxed">Dengan memohon rahmat Allah, kami mengundang kehadiran Bapak/Buku/Saudara/i sekalian.</p>
                                </div>

                                <div class="p-4 rounded-xl bg-[#faf8f5] border border-rose-gold-100/35 space-y-3">
                                    <span class="text-[11px] font-bold text-rose-gold-700 uppercase tracking-wider">Akad Nikah</span>
                                    <div class="h-[1px] w-6 bg-rose-gold-200 mx-auto"></div>
                                    <p class="text-[10px] text-zinc-700 font-bold">{{ $eventDateFormatted }}</p>
                                    <p class="text-[9px] text-zinc-500">Pukul 09:00 WIB - Selesai</p>
                                    <p class="text-[9px] text-zinc-600 font-bold leading-normal">{{ $venue }}</p>
                                </div>
                                
                                <div class="p-4 rounded-xl bg-[#faf8f5] border border-rose-gold-100/35 space-y-3">
                                    <span class="text-[11px] font-bold text-rose-gold-700 uppercase tracking-wider">Resepsi</span>
                                    <div class="h-[1px] w-6 bg-rose-gold-200 mx-auto"></div>
                                    <p class="text-[10px] text-zinc-700 font-bold">{{ $eventDateFormatted }}</p>
                                    <p class="text-[9px] text-zinc-500">Pukul 11:00 WIB - Selesai</p>
                                    <p class="text-[9px] text-zinc-600 font-bold leading-normal">{{ $venue }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
