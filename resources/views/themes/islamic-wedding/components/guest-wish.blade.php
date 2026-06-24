<section 
    class="py-16 px-6 border-b border-stone-200/50 space-y-8" 
    data-animation="fade-up"
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
        <h2 class="text-2xl font-bold uppercase tracking-widest" style="font-family: var(--font-heading);">Doa & Ucapan</h2>
        <div class="h-[1px] w-12 bg-current mx-auto mt-2 opacity-50"></div>
    </div>

    <!-- Wishes Container -->
    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1 max-w-md mx-auto">
        <template x-for="wish in wishes" :key="wish.name + wish.time">
            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 space-y-2 shadow-sm text-left">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider" x-text="wish.name"></h4>
                    <span class="text-[10px] opacity-40" x-text="wish.time"></span>
                </div>
                <p class="text-xs opacity-80 leading-relaxed" x-text="wish.message"></p>
            </div>
        </template>

        <div x-show="wishes.length === 0" class="text-center py-8 opacity-40 text-xs">
            Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
        </div>
    </div>
</section>