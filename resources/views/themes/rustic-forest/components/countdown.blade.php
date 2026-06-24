<section class="countdown-section py-12 px-4 text-center border-b border-stone-200/50" data-animation="fade-up">
    <div class="max-w-xl mx-auto">
        <h3 class="countdown-title text-sm sm:text-base uppercase tracking-widest font-semibold mb-6">
            Menuju Hari Bahagia
        </h3>
        
        <div 
            x-data="countdownTimer('{{ $invitation->reception_date ? $invitation->reception_date->format('Y-m-d\TH:i:s') : ( $invitation->akad_date ? $invitation->akad_date->format('Y-m-d\TH:i:s') : '' ) }}')"
            class="grid grid-cols-4 gap-2 sm:gap-4 max-w-sm sm:max-w-md mx-auto"
        >
            <!-- Hari -->
            <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight" x-text="days">00</span>
                <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Hari</span>
            </div>
            
            <!-- Jam -->
            <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight" x-text="hours">00</span>
                <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Jam</span>
            </div>
            
            <!-- Menit -->
            <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight" x-text="minutes">00</span>
                <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Menit</span>
            </div>
            
            <!-- Detik -->
            <div class="countdown-card flex flex-col justify-center items-center p-3 sm:p-4 rounded-xl bg-white shadow-sm border border-stone-100">
                <span class="countdown-number text-2xl sm:text-4xl font-bold tracking-tight" x-text="seconds">00</span>
                <span class="countdown-label text-[9px] sm:text-xs uppercase tracking-wider opacity-60 mt-1">Detik</span>
            </div>
        </div>
    </div>
</section>

@once
<script>
    document.addEventListener('alpine:init', () => {
        if (window.countdownTimerInited) return;
        window.countdownTimerInited = true;
        
        Alpine.data('countdownTimer', (targetDateStr) => ({
            targetDate: targetDateStr ? new Date(targetDateStr.replace(/-/g, '/')).getTime() : null,
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
@endonce