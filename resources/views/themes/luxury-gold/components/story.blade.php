<section class="story-section py-16 px-4 border-b border-stone-200/50" {!! themeAnimation('story') !!}>
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <span class="story-subtitle text-xs uppercase tracking-widest font-semibold">Kisah Kami</span>
            <h3 class="story-title text-2xl sm:text-3xl font-bold mt-1">Perjalanan Cinta</h3>
            <p class="story-desc text-xs sm:text-sm opacity-70 mt-2 max-w-md mx-auto">
                Bagaimana Tuhan mempertemukan kami dalam bingkai takdir yang indah
            </p>
        </div>

        @if(count($invitationData['story']) > 0)
            <div class="relative max-w-md mx-auto">
                <!-- Center timeline line -->
                <div class="absolute left-4 top-0 bottom-0 w-[2px] bg-[var(--theme-secondary)] story-line"></div>
                
                <div class="space-y-12 relative">
                    @foreach(collect($invitationData['story'])->sortBy('sort') as $index => $item)
                        <div class="flex flex-col items-stretch relative" data-gsap="fade-up">
                            <!-- Timeline circle dot -->
                            <div class="absolute left-4 -translate-x-1/2 flex items-center justify-center z-10">
                                <div class="w-8 h-8 rounded-full bg-[var(--theme-surface)] border-2 border-[var(--theme-primary)] flex items-center justify-center shadow-sm story-dot" style="border-radius: 2px;">
                                    <span class="text-xs font-bold text-[var(--theme-primary)]">{{ $index + 1 }}</span>
                                </div>
                            </div>
                            
                            <!-- Story Card -->
                            <div class="w-full pl-12">
                                <div class="story-card bg-[var(--theme-surface)] p-6 border border-[var(--theme-secondary)] shadow-sm relative hover:shadow-md transition duration-300 text-left" style="border-radius: 4px;">
                                    <span class="story-card-date text-xs font-bold tracking-wider">
                                        {{ format_date_safe($item['date']) }}
                                    </span>
                                    <h4 class="story-card-title text-lg font-bold mt-1 leading-snug">
                                        {{ $item['title'] }}
                                    </h4>
                                    @if(!empty($item['image']))
                                        <div class="mt-3 mb-2 overflow-hidden border border-[var(--theme-secondary)] aspect-video" style="border-radius: 2px;">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover" />
                                        </div>
                                    @endif
                                    <p class="story-card-desc text-xs sm:text-sm opacity-80 leading-relaxed mt-2 whitespace-pre-line">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-8 text-stone-400 text-sm italic">
                Belum ada kisah perjalanan cinta yang dibagikan.
            </div>
        @endif
    </div>
</section>