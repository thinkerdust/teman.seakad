{{-- 
    Event Section — Wedding Invitation Card Style (Phase 2.1)
    
    Composition:
    - Gold top accent line
    - Icon illustrations (calendar, location)
    - Elegant typography
    - Map button
--}}

@php
    $themeFolder = $invitation->theme->folder;
@endphp

<section class="py-12 px-4 relative overflow-hidden" style="border-bottom: 1px solid var(--theme-secondary);" {!! themeAnimation('event') !!}>
    {{-- Side decorations --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'rose-02', 'class' => 'left-2 top-20 opacity-15'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'leaf-02', 'class' => 'right-2 bottom-20 opacity-15'])

    <div class="max-w-4xl mx-auto relative z-10">
        {{-- Section Header --}}
        <div class="section-header fade-up" data-animation>
            <span class="section-subtitle">Acara</span>
            <h3 class="section-title">Detail Acara</h3>
            <div class="section-line"></div>
            <p class="section-desc">
                Detail pelaksanaan hari istimewa kami yang sangat kami harapkan kehadiran Anda
            </p>
        </div>

        @if(count($invitationData['events']) > 0)
            <div class="space-y-6 max-w-md mx-auto">
                @foreach($invitationData['events'] as $eventIndex => $event)
                    <div class="event-invitation-card fade-up" data-animation style="animation-delay: {{ $eventIndex * 0.2 }}s;">
                        <div class="space-y-5">
                            {{-- Event Name --}}
                            <div class="text-center">
                                <h4 class="event-name">{{ $event['name'] }}</h4>
                                <div class="section-line mt-2"></div>
                            </div>

                            {{-- Event Details --}}
                            <div class="space-y-4">
                                {{-- Tanggal --}}
                                <div class="event-detail-row slide-left" data-animation style="animation-delay: {{ 0.3 + $eventIndex * 0.2 }}s;">
                                    <div class="event-detail-icon p-1" style="background: var(--theme-gold-glow); border-radius: var(--theme-radius-sm);">
                                        <img src="{{ themeAsset('icon.calendar') }}" alt="Tanggal" class="w-full h-full object-contain" />
                                    </div>
                                    <div>
                                        <span class="event-detail-label">Tanggal</span>
                                        <span class="event-detail-value block">{{ $event['date'] ? Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') : '-' }}</span>
                                    </div>
                                </div>

                                {{-- Waktu --}}
                                <div class="event-detail-row slide-left" data-animation style="animation-delay: {{ 0.4 + $eventIndex * 0.2 }}s;">
                                    <div class="event-detail-icon flex items-center justify-center" style="background: var(--theme-gold-glow); border-radius: var(--theme-radius-sm);">
                                        <svg class="w-5 h-5" style="color: var(--theme-primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="event-detail-label">Waktu</span>
                                        <span class="event-detail-value block">{{ $event['time'] }}</span>
                                    </div>
                                </div>

                                {{-- Lokasi --}}
                                <div class="event-detail-row slide-left" data-animation style="animation-delay: {{ 0.5 + $eventIndex * 0.2 }}s;">
                                    <div class="event-detail-icon p-1" style="background: var(--theme-gold-glow); border-radius: var(--theme-radius-sm);">
                                        <img src="{{ themeAsset('icon.location') }}" alt="Lokasi" class="w-full h-full object-contain" />
                                    </div>
                                    <div>
                                        <span class="event-detail-label">Tempat</span>
                                        <p class="event-detail-value leading-relaxed">{{ $event['location'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Map Button --}}
                        @if($invitationData['maps_url'])
                            <div class="mt-5 pt-4" style="border-top: 1px solid var(--theme-secondary);">
                                <a href="{{ $invitationData['maps_url'] }}" 
                                   target="_blank" 
                                   class="event-map-btn">
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
            <div class="text-center py-8 opacity-40 text-sm italic" style="color: var(--theme-text);">
                Belum ada rincian acara yang dibagikan.
            </div>
        @endif
    </div>
</section>