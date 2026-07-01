{{-- 
    Music — Premium Floating Circular Button
    
    Features:
    - Circular floating button
    - Spin-slow when active
    - Pulsing glow rings
    - Uses theme primary variable
    - localStorage state persistence
--}}

<div 
    x-data="musicPlayer()"
    class="fixed bottom-6 right-6 z-[999]"
>
    {{-- Hidden Audio Element --}}
    <audio 
        id="bg-music-player" 
        src="{{ !empty($music['file']) ? asset($music['file']) : themeAsset('audio') }}" 
        loop
    ></audio>

    <div class="music-btn-container">
        {{-- Pulsing Glow Ring 1 --}}
        <div 
            x-show="isPlaying"
            class="music-ring music-ring--ping"
        ></div>
        {{-- Pulsing Glow Ring 2 --}}
        <div 
            x-show="isPlaying"
            class="music-ring music-ring--pulse"
        ></div>

        {{-- Music Control Button --}}
        <button 
            @click="toggle"
            :class="isPlaying ? 'music-btn is-playing' : 'music-btn'"
            title="Musik Latar"
        >
            {{-- Playing state icon --}}
            <svg x-show="isPlaying" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
            {{-- Paused state icon --}}
            <svg x-show="!isPlaying" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
            </svg>
        </button>
    </div>
</div>

@once
<script>
    document.addEventListener('alpine:init', () => {
        if (window.musicPlayerInited) return;
        window.musicPlayerInited = true;
        
        Alpine.data('musicPlayer', () => ({
            isPlaying: false,
            audio: null,
 
            init() {
                this.audio = document.getElementById('bg-music-player');
                if (!this.audio) return;

                // Cek status terakhir dari localStorage
                const savedState = localStorage.getItem('music_playing');

                window.addEventListener('invitation-opened', () => {
                    this.playMusic();
                });

                // Jika status sebelumnya adalah aktif (misalnya reload setelah dibuka)
                if (savedState === 'true') {
                    this.audio.play().then(() => {
                        this.isPlaying = true;
                    }).catch(err => {
                        console.log('Autoplay blocked. Waiting for user interaction...');
                        // Tambahkan fallback listener sekali klik jika diblokir
                        const playFallback = () => {
                            if (this.audio) {
                                this.audio.play().then(() => {
                                    this.isPlaying = true;
                                    localStorage.setItem('music_playing', 'true');
                                    document.removeEventListener('click', playFallback);
                                }).catch(e => console.log('Playback failed on click:', e));
                            }
                        };
                        document.addEventListener('click', playFallback);
                    });
                }
            },

            playMusic() {
                if (!this.audio) return;
                this.audio.play().then(() => {
                    this.isPlaying = true;
                    localStorage.setItem('music_playing', 'true');
                }).catch(err => {
                    console.log('Autoplay on open blocked:', err);
                });
            },

            toggle() {
                if (!this.audio) return;
                
                if (this.isPlaying) {
                    this.audio.pause();
                    this.isPlaying = false;
                    localStorage.setItem('music_playing', 'false');
                } else {
                    this.audio.play()
                        .then(() => {
                            this.isPlaying = true;
                            localStorage.setItem('music_playing', 'true');
                        })
                        .catch(err => {
                            console.log('Playback failed:', err);
                        });
                }
            }
        }));
    });
</script>
@endonce