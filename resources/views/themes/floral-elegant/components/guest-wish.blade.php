{{-- 
    Guest Wish — Doa & Ucapan
    
    Premium styling with theme tokens.
    Scrollable container with custom scrollbar.
    Real-time update when RSVP submitted.
--}}

<section 
    class="py-12 px-6 space-y-8 relative"
    style="border-bottom: 1px solid var(--theme-secondary);"
    {!! themeAnimation('guest-wish') !!}
    x-data='{
        wishes: @json($invitation->guests->whereNotNull("message")->sortByDesc("created_at")->map(function($g) {
            return [
                "name" => $g->name,
                "message" => $g->message,
                "time" => $g->created_at ? $g->created_at->diffForHumans() : "Baru saja"
            ];
        })->values()->all()),
        init() {
            window.addEventListener("rsvp-submitted", (e) => {
                if (e.detail && e.detail.message) {
                    this.wishes.unshift({
                        name: e.detail.name,
                        message: e.detail.message,
                        time: "Baru saja"
                    });
                }
            });
        }
    }'
>
    {{-- Section Header --}}
    <div class="section-header fade-up" data-animation>
        <h2 class="section-title" style="font-family: var(--theme-font-heading); color: var(--theme-primary);">Doa & Ucapan</h2>
        <div class="section-line"></div>
    </div>

    {{-- Wishes Container --}}
    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 max-w-md mx-auto custom-scrollbar fade-up" data-animation style="animation-delay: 0.15s;">
        <template x-for="wish in wishes" :key="wish.name + wish.time">
            <div class="p-4 space-y-1.5 text-left shadow-sm transition duration-300"
                 style="background: var(--theme-surface); border: 1px solid var(--theme-secondary); border-radius: var(--theme-radius-lg);">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider" 
                        style="color: var(--theme-primary); font-family: var(--theme-font-body);" 
                        x-text="wish.name"></h4>
                    <span class="text-[10px] opacity-40" 
                          style="color: var(--theme-text); font-family: var(--theme-font-body);" 
                          x-text="wish.time"></span>
                </div>
                <p class="text-xs opacity-75 leading-relaxed" 
                   style="color: var(--theme-text); font-family: var(--theme-font-body);" 
                   x-text="wish.message"></p>
            </div>
        </template>

        <div x-show="wishes.length === 0" 
             class="text-center py-8 text-xs opacity-45" 
             style="color: var(--theme-text); font-family: var(--theme-font-body);">
            Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
        </div>
    </div>
</section>