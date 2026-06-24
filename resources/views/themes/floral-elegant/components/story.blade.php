<section class="story-section py-16 px-4 border-b border-stone-200/50" data-animation="fade-up">
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
                <div class="absolute left-4 top-0 bottom-0 w-[2px] bg-stone-200 story-line"></div>
                
                <div class="space-y-12 relative">
                    @foreach(collect($invitationData['story'])->sortBy('sort') as $index => $item)
                        <div class="flex flex-col items-stretch relative">
                            <!-- Timeline circle dot -->
                            <div class="absolute left-4 -translate-x-1/2 flex items-center justify-center z-10">
                                <div class="w-8 h-8 rounded-full bg-white border-2 flex items-center justify-center shadow-sm story-dot">
                                    <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                </div>
                            </div>
                            
                            <!-- Story Card -->
                            <div class="w-full pl-12">
                                <div class="story-card bg-white p-6 rounded-2xl border border-stone-200/80 shadow-sm relative hover:shadow-md transition duration-300 text-left">
                                    <span class="story-card-date text-xs font-bold tracking-wider">
                                        {{ $item['date'] ? Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') : '-' }}
                                    </span>
                                    <h4 class="story-card-title text-lg font-bold mt-1 leading-snug">
                                        {{ $item['title'] }}
                                    </h4>
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