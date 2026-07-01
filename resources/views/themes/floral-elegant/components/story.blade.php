{{-- 
    Story Timeline — Premium Love Journey (Phase 2.1)
    
    Premium timeline with:
    - Vertical line connector
    - Numbered dots
    - Story cards with image, date, title, description
    - Floral ornament accents
    - Scroll reveal animation
--}}

@php
    $themeFolder = $invitation->theme->folder;
@endphp

<section class="py-12 px-4 relative overflow-hidden" style="border-bottom: 1px solid var(--theme-secondary);" {!! themeAnimation('story') !!}>
    {{-- Side decorations for timeline storytelling --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'leaf-01', 'class' => 'left-2 top-1/4 opacity-15'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'leaf-02', 'class' => 'right-2 bottom-1/4 opacity-15'])

    <div class="max-w-4xl mx-auto relative z-10">
        {{-- Section Header --}}
        <div class="section-header fade-up" data-animation>
            <span class="section-subtitle">Kisah Kami</span>
            <h3 class="section-title">Perjalanan Cinta</h3>
            <div class="section-line"></div>
            <p class="section-desc">
                Bagaimana Tuhan mempertemukan kami dalam bingkai takdir yang indah
            </p>
        </div>

        {{-- Center Rose Accent --}}
        <div class="flex justify-center mb-6 fade-in" data-animation>
            @include('themes.' . $themeFolder . '.components.theme-decoration', [
                'type' => 'rose-01',
                'class' => 'relative block',
                'style' => 'position: relative; width: 2.5rem; height: 2.5rem; opacity: 0.35;'
            ])
        </div>

        @if(count($invitationData['story']) > 0)
            <div class="story-timeline">
                {{-- Timeline Line --}}
                <div class="story-timeline-line"></div>

                <div class="space-y-0">
                    @foreach(collect($invitationData['story'])->sortBy('sort') as $index => $item)
                        <div class="story-timeline-item fade-up" data-animation style="animation-delay: {{ $index * 0.15 }}s;">
                            {{-- Timeline Dot --}}
                            <div class="story-timeline-dot">
                                <span>{{ $index + 1 }}</span>
                            </div>
                            
                            {{-- Story Card --}}
                            <div class="story-card">
                                <span class="story-card-date">
                                    {{ format_date_safe($item['date']) }}
                                </span>
                                <h4 class="story-card-title mt-1">
                                    {{ $item['title'] }}
                                </h4>
                                @if(!empty($item['image']))
                                    <div class="story-card-image hover-zoom mt-3">
                                        <img src="{{ $item['image'] }}" 
                                             alt="{{ $item['title'] }}" 
                                             class="w-full h-full object-cover"
                                             loading="lazy" />
                                    </div>
                                @endif
                                <p class="story-card-desc mt-3 text-xs leading-relaxed opacity-85">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-8 opacity-40 text-sm italic" style="color: var(--theme-text);">
                Belum ada kisah perjalanan cinta yang dibagikan.
            </div>
        @endif
    </div>
</section>