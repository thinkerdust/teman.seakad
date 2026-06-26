<section 
    class="py-16 px-6 border-b border-[var(--theme-secondary)]/50 space-y-8" 
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
    <div class="text-center space-y-2">
        <h2 class="text-2xl font-bold uppercase tracking-widest text-[var(--theme-primary)]" style="font-family: var(--theme-font-heading);">Doa & Ucapan</h2>
        <div class="h-[1px] w-12 bg-[var(--theme-primary)] mx-auto mt-2 opacity-50"></div>
    </div>

    <!-- Wishes Container -->
    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 max-w-md mx-auto custom-scrollbar">
        <template x-for="wish in wishes" :key="wish.name + wish.time">
            <div class="bg-[var(--theme-surface)]/80 border border-[var(--theme-secondary)]/60 p-5 space-y-2 shadow-sm text-left" style="border-radius: 1rem 0 1rem 0;">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--theme-primary)] font-body" style="font-family: var(--theme-font-body);" x-text="wish.name"></h4>
                    <span class="text-[10px] text-[var(--theme-text)] opacity-40 font-body" style="font-family: var(--theme-font-body);" x-text="wish.time"></span>
                </div>
                <p class="text-xs text-[var(--theme-text)] opacity-80 leading-relaxed font-body" style="font-family: var(--theme-font-body);" x-text="wish.message"></p>
            </div>
        </template>

        <div x-show="wishes.length === 0" class="text-center py-8 text-[var(--theme-text)] opacity-50 text-xs font-body" style="font-family: var(--theme-font-body);">
            Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
        </div>
    </div>
</section>