<section 
    class="py-16 px-6 border-b border-neutral-850 space-y-8" 
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
        <h2 class="font-heading text-2xl text-amber-300 font-bold uppercase tracking-widest">Doa & Ucapan</h2>
        <div class="h-[1px] w-12 bg-amber-400 mx-auto mt-2"></div>
    </div>

    <!-- Wishes Container -->
    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1">
        <template x-for="wish in wishes" :key="wish.name + wish.time">
            <div class="bg-neutral-950/30 border border-neutral-850/60 rounded-2xl p-5 space-y-2">
                <div class="flex items-center justify-between">
                    <h4 class="font-heading text-xs font-bold text-amber-100 uppercase tracking-wider" x-text="wish.name"></h4>
                    <span class="text-[10px] text-neutral-500" x-text="wish.time"></span>
                </div>
                <p class="text-xs text-neutral-450 leading-relaxed" x-text="wish.message"></p>
            </div>
        </template>

        <div x-show="wishes.length === 0" class="text-center py-8 text-neutral-500 text-xs">
            Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
        </div>
    </div>
</section>
