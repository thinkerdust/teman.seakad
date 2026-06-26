<section class="py-16 px-6 border-b border-neutral-850 space-y-12" {!! themeAnimation('story') !!}>
    <div class="text-center space-y-2">
        <h2 class="font-heading text-2xl text-[var(--theme-primary)] font-bold uppercase tracking-widest">Kisah Cinta</h2>
        <div class="h-[1px] w-12 bg-[var(--theme-accent)] mx-auto mt-2"></div>
    </div>

    <div class="relative border-l border-[var(--theme-secondary)] ml-3 space-y-8">
        @foreach($invitationData['story'] as $story)
            <div class="relative pl-6" data-gsap="fade-up">
                <!-- Timeline dot -->
                <div class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full bg-[var(--theme-primary)] ring-4 ring-neutral-900 border border-[var(--theme-accent)]"></div>
                
                <div class="space-y-1">
                    <span class="inline-block text-[10px] font-bold text-[var(--theme-accent)] uppercase tracking-widest bg-[var(--theme-accent)]/10 px-2 py-0.5 rounded-full border border-[var(--theme-accent)]/20">
                        {{ $story['date'] }}
                    </span>
                    <h3 class="font-heading text-md font-bold text-white">{{ $story['title'] }}</h3>
                    <p class="text-xs text-neutral-400 leading-relaxed">{{ $story['description'] }}</p>
                    @if(!empty($story['image']))
                        <div class="mt-3 overflow-hidden rounded-xl border border-neutral-800 aspect-video">
                            <img src="{{ $story['image'] }}" alt="{{ $story['title'] }}" class="w-full h-full object-cover" />
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
