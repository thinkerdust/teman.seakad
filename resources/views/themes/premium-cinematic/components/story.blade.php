<section class="py-16 px-6 border-b border-neutral-850 space-y-12" data-animation="fade-up">
    <div class="text-center space-y-2">
        <h2 class="font-heading text-2xl text-amber-300 font-bold uppercase tracking-widest">Kisah Cinta</h2>
        <div class="h-[1px] w-12 bg-amber-400 mx-auto mt-2"></div>
    </div>

    <div class="relative border-l border-neutral-800 ml-3 space-y-8">
        @foreach($invitationData['story'] as $story)
            <div class="relative pl-6">
                <!-- Timeline dot -->
                <div class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full bg-amber-400 ring-4 ring-neutral-900 border border-amber-200"></div>
                
                <div class="space-y-1">
                    <span class="inline-block text-[10px] font-bold text-amber-300 uppercase tracking-widest bg-amber-400/5 px-2 py-0.5 rounded-full border border-amber-400/10">
                        {{ $story['date'] }}
                    </span>
                    <h3 class="font-heading text-md font-bold text-white">{{ $story['title'] }}</h3>
                    <p class="text-xs text-neutral-400 leading-relaxed">{{ $story['description'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
