{{-- 
    Countdown — Glass Premium Style
    
    From plain numbers to premium glass card countdown
    with soft pulse animation and theme variables.
--}}

<section class="py-12 px-4 text-center relative" style="border-bottom: 1px solid var(--theme-secondary);" {!! themeAnimation('countdown') !!}>
    <div class="max-w-xl mx-auto">
        {{-- Title --}}
        <div class="fade-up" data-animation>
            <h3 class="section-subtitle mb-1" style="color: var(--theme-primary);">Menghitung Hari</h3>
            <p class="section-title text-lg" style="font-family: var(--theme-font-heading);">Menuju Hari Bahagia</p>
            <div class="section-line mt-2 mb-8"></div>
        </div>
        
        {{-- Countdown Grid --}}
        <div 
            x-data="countdownTimer('{{ $invitation->reception_date ? $invitation->reception_date->format('Y-m-d\TH:i:s') : ( $invitation->akad_date ? $invitation->akad_date->format('Y-m-d\TH:i:s') : '' ) }}')"
            class="grid grid-cols-4 gap-2 sm:gap-4 max-w-sm sm:max-w-md mx-auto fade-up"
            data-animation
            style="animation-delay: 0.2s;"
        >
            {{-- Hari --}}
            <div class="countdown-glass-card soft-pulse" style="animation-delay: 0s;">
                <span class="countdown-number" x-text="days">00</span>
                <span class="countdown-label">Hari</span>
            </div>
            
            {{-- Jam --}}
            <div class="countdown-glass-card soft-pulse" style="animation-delay: 0.5s;">
                <span class="countdown-number" x-text="hours">00</span>
                <span class="countdown-label">Jam</span>
            </div>
            
            {{-- Menit --}}
            <div class="countdown-glass-card soft-pulse" style="animation-delay: 1s;">
                <span class="countdown-number" x-text="minutes">00</span>
                <span class="countdown-label">Menit</span>
            </div>
            
            {{-- Detik --}}
            <div class="countdown-glass-card soft-pulse" style="animation-delay: 1.5s;">
                <span class="countdown-number" x-text="seconds">00</span>
                <span class="countdown-label">Detik</span>
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