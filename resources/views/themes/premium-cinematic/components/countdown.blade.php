<section class="py-12 px-6 bg-neutral-950/40 border-b border-neutral-850 text-center space-y-6" data-animation="fade-up">
    <h3 class="font-heading text-lg text-amber-300 font-bold uppercase tracking-widest">Hari Bahagia</h3>
    
    <div 
        x-data="countdownTimer('{{ $invitation->akad_date ? $invitation->akad_date->format('Y-m-d\TH:i:s') : '' }}')"
        class="grid grid-cols-4 gap-2 max-w-xs mx-auto text-white"
    >
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-3">
            <span class="block text-2xl font-bold font-heading text-amber-200" x-text="days">00</span>
            <span class="text-[10px] text-neutral-400 uppercase tracking-wider">Hari</span>
        </div>
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-3">
            <span class="block text-2xl font-bold font-heading text-amber-200" x-text="hours">00</span>
            <span class="text-[10px] text-neutral-400 uppercase tracking-wider">Jam</span>
        </div>
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-3">
            <span class="block text-2xl font-bold font-heading text-amber-200" x-text="minutes">00</span>
            <span class="text-[10px] text-neutral-400 uppercase tracking-wider">Menit</span>
        </div>
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-3">
            <span class="block text-2xl font-bold font-heading text-amber-200" x-text="seconds">00</span>
            <span class="text-[10px] text-neutral-400 uppercase tracking-wider">Detik</span>
        </div>
    </div>
</section>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('countdownTimer', (targetDateStr) => ({
            targetDate: targetDateStr ? new Date(targetDateStr).getTime() : null,
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
            
            init() {
                if (!this.targetDate) return;
                this.update();
                setInterval(() => this.update(), 1000);
            },
            
            update() {
                const now = new Date().getTime();
                const difference = this.targetDate - now;
                
                if (difference < 0) {
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    return;
                }
                
                const d = Math.floor(difference / (1000 * 60 * 60 * 24));
                const h = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((difference % (1000 * 60)) / 1000);
                
                this.days = d < 10 ? '0' + d : d;
                this.hours = h < 10 ? '0' + h : h;
                this.minutes = m < 10 ? '0' + m : m;
                this.seconds = s < 10 ? '0' + s : s;
            }
        }));
    });
</script>
