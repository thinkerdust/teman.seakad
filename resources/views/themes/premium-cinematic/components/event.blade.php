<section class="py-16 px-6 border-b border-neutral-850 space-y-12" data-animation="fade-up">
    <div class="text-center space-y-2">
        <h2 class="font-heading text-2xl text-amber-300 font-bold uppercase tracking-widest">Informasi Acara</h2>
        <div class="h-[1px] w-12 bg-amber-400 mx-auto mt-2"></div>
    </div>

    <div class="space-y-8">
        @foreach($invitationData['events'] as $event)
            <div class="bg-neutral-950/30 border border-neutral-850/60 rounded-2xl p-6 text-center space-y-4">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-amber-400/5 text-amber-250 border border-amber-400/10">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                
                <div class="space-y-2">
                    <h3 class="font-heading text-lg font-bold text-white uppercase tracking-wider">{{ $event['name'] }}</h3>
                    <div class="h-[1px] w-8 bg-amber-400/50 mx-auto"></div>
                </div>

                <div class="space-y-1 text-xs text-neutral-350">
                    <p class="font-semibold text-amber-100">
                        {{ $event['date'] ? Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') : '-' }}
                    </p>
                    <p>Pukul: {{ $event['time'] }}</p>
                    <p class="mt-2 text-neutral-400 font-medium">{{ $event['location'] }}</p>
                </div>

                @if($invitationData['maps_url'])
                    <div class="pt-2">
                        <a 
                            href="{{ $invitationData['maps_url'] }}" 
                            target="_blank"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-400/5 border border-amber-400/20 text-amber-200 text-xs font-semibold hover:bg-amber-400/10 transition"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Buka Google Maps
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</section>
