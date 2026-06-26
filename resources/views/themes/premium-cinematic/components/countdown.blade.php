<section class="py-12 px-6 bg-[var(--theme-surface)]/20 border-b border-[var(--theme-secondary)]/30 text-center space-y-6" {!! themeAnimation('countdown') !!}>
    <h3 class="font-heading text-lg text-[var(--theme-primary)] font-bold uppercase tracking-widest" style="font-family: var(--theme-font-heading);">Hari Bahagia</h3>
    
    <div 
        x-data="countdownTimer('{{ $invitation->akad_date ? $invitation->akad_date->format('Y-m-d\TH:i:s') : '' }}')"
        class="grid grid-cols-4 gap-2 max-w-xs mx-auto text-[var(--theme-text)]"
    >
        <div class="backdrop-blur-md bg-[var(--theme-surface)]/60 border border-[var(--theme-secondary)]/40 rounded-xl p-3 shadow-md">
            <span class="block text-2xl font-bold font-heading text-[var(--theme-primary)]" style="font-family: var(--theme-font-heading);" x-text="days">00</span>
            <span class="text-[10px] text-[var(--theme-text)] opacity-70 uppercase tracking-wider font-body" style="font-family: var(--theme-font-body);">Hari</span>
        </div>
        <div class="backdrop-blur-md bg-[var(--theme-surface)]/60 border border-[var(--theme-secondary)]/40 rounded-xl p-3 shadow-md">
            <span class="block text-2xl font-bold font-heading text-[var(--theme-primary)]" style="font-family: var(--theme-font-heading);" x-text="hours">00</span>
            <span class="text-[10px] text-[var(--theme-text)] opacity-70 uppercase tracking-wider font-body" style="font-family: var(--theme-font-body);">Jam</span>
        </div>
        <div class="backdrop-blur-md bg-[var(--theme-surface)]/60 border border-[var(--theme-secondary)]/40 rounded-xl p-3 shadow-md">
            <span class="block text-2xl font-bold font-heading text-[var(--theme-primary)]" style="font-family: var(--theme-font-heading);" x-text="minutes">00</span>
            <span class="text-[10px] text-[var(--theme-text)] opacity-70 uppercase tracking-wider font-body" style="font-family: var(--theme-font-body);">Menit</span>
        </div>
        <div class="backdrop-blur-md bg-[var(--theme-surface)]/60 border border-[var(--theme-secondary)]/40 rounded-xl p-3 shadow-md">
            <span class="block text-2xl font-bold font-heading text-[var(--theme-primary)]" style="font-family: var(--theme-font-heading);" x-text="seconds">00</span>
            <span class="text-[10px] text-[var(--theme-text)] opacity-70 uppercase tracking-wider font-body" style="font-family: var(--theme-font-body);">Detik</span>
        </div>
    </div>
</section>

@once
<script>
    document.addEventListener('alpine:init', () => {
        if (window.countdownTimerInited) return;
        window.countdownTimerInited = true;
        
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
@endonce
