<section class="events-section py-16 px-4 border-b border-stone-200/50" {!! themeAnimation('event') !!}>
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <span class="events-subtitle text-xs uppercase tracking-widest font-semibold">Acara</span>
            <h3 class="events-title text-2xl sm:text-3xl font-bold mt-1">Detail Acara</h3>
            <p class="events-desc text-xs sm:text-sm opacity-70 mt-2 max-w-md mx-auto">
                Detail pelaksanaan hari istimewa kami yang sangat kami harapkan kehadiran Anda
            </p>
        </div>

        @if(count($invitationData['events']) > 0)
            <div class="space-y-6 max-w-md mx-auto">
                @foreach($invitationData['events'] as $event)
                    <div class="event-card bg-[var(--theme-surface)] p-6 sm:p-8 rounded-2xl border border-[var(--theme-secondary)] shadow-sm flex flex-col justify-between relative overflow-hidden text-left" data-gsap="fade-up">
                        <div class="relative z-10 space-y-6">
                            <div class="text-center">
                                <h4 class="event-name text-xl sm:text-2xl font-bold">
                                    {{ $event['name'] }}
                                </h4>
                                <div class="h-[1px] w-12 bg-[var(--theme-secondary)] mx-auto my-3 event-card-divider"></div>
                            </div>

                            <div class="space-y-4">
                                <!-- Tanggal -->
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 p-1.5 rounded-lg bg-[var(--theme-primary)]/10 text-[var(--theme-primary)]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="block text-[9px] uppercase font-bold opacity-50 tracking-wider">Tanggal</span>
                                        <span class="text-xs sm:text-sm font-semibold">{{ $event['date'] ? Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') : '-' }}</span>
                                    </div>
                                </div>

                                <!-- Waktu -->
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 p-1.5 rounded-lg bg-[var(--theme-primary)]/10 text-[var(--theme-primary)]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="block text-[9px] uppercase font-bold opacity-50 tracking-wider">Waktu</span>
                                        <span class="text-xs sm:text-sm font-semibold">{{ $event['time'] }}</span>
                                    </div>
                                </div>

                                <!-- Lokasi -->
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 p-1.5 rounded-lg bg-[var(--theme-primary)]/10 text-[var(--theme-primary)]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="block text-[9px] uppercase font-bold opacity-50 tracking-wider">Tempat</span>
                                        <p class="text-xs sm:text-sm leading-relaxed">{{ $event['location'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($invitationData['maps_url'])
                            <div class="mt-6 pt-4 border-t border-[var(--theme-secondary)]/50 event-card-footer">
                                <a 
                                    href="{{ $invitationData['maps_url'] }}" 
                                    target="_blank" 
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl text-white text-xs sm:text-sm font-semibold py-2.5 px-4 shadow transition duration-200 bg-[var(--theme-primary)] hover:brightness-95"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    Buka Peta Lokasi
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-stone-400 text-sm italic">
                Belum ada rincian acara yang dibagikan.
            </div>
        @endif
    </div>
</section>