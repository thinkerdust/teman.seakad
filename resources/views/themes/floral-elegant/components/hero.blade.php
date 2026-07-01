{{-- 
    Hero — Premium Cover Opening Experience (Phase 2.1)
    
    Composition layers (bottom to top):
    Layer 1: Background (background.cover)
    Layer 2: Watercolor texture (background.watercolor)
    Layer 3: Paper texture (background.paper)
    Layer 4: Flower corner left/right/bottom (theme-decoration components)
    Layer 5: Sparkle particles (theme-decoration sparkle)
    Layer 6: Couple illustration (illustration.couple)
    Layer 7: Envelope icon (illustration.envelope)
    Layer 8: Content text
    
    Animation sequence: bg fade → floral reveal → couple scale → names stagger → button pulse
--}}

@php
    $themeFolder = $invitation->theme->folder;
@endphp

<div id="cover-overlay" 
     {!! themeAnimation('hero') !!} 
     class="hero-cover fixed inset-0 z-[9999] p-6 transition-transform duration-1000"
     style="background-color: var(--theme-background);"
>
    {{-- Layer 1: Background Cover Image --}}
    <div class="hero-bg-layer opacity-0" 
         id="hero-bg" 
         style="background-image: url('{{ themeAsset('background.cover') }}'); z-index: 0;">
    </div>

    {{-- Layer 2: Watercolor Texture --}}
    <div class="hero-watercolor-layer" 
         style="background-image: url('{{ themeAsset('background.watercolor') }}'); z-index: 1;">
    </div>

    {{-- Layer 3: Paper Texture Overlay --}}
    <div class="absolute inset-0 opacity-15" 
         style="background-image: url('{{ themeAsset('background.paper') }}'); background-repeat: repeat; z-index: 2; mix-blend-mode: multiply; pointer-events: none;">
    </div>

    {{-- Layer 4: Floral Corners (Theme Decoration Components) --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', [
        'type' => 'corner-top-left',
        'class' => 'hero-floral-corner hero-floral-corner--top-left',
        'style' => 'z-index: 3; max-height: 180px;'
    ])

    @include('themes.' . $themeFolder . '.components.theme-decoration', [
        'type' => 'corner-top-right',
        'class' => 'hero-floral-corner hero-floral-corner--top-right',
        'style' => 'z-index: 3; max-height: 180px;'
    ])

    @include('themes.' . $themeFolder . '.components.theme-decoration', [
        'type' => 'corner-bottom-right',
        'class' => 'hero-floral-corner hero-floral-corner--bottom-right',
        'style' => 'z-index: 3; max-height: 160px; transform: scaleX(-1);'
    ])

    {{-- Layer 5: Sparkle Overlay --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'sparkle'])

    {{-- Layer 5.1: Floral Petals (Floating Particles) --}}
    <div class="floral-particles-container" id="particles-container" style="z-index: 4;"></div>

    {{-- Layer 6 & 7 & 8: Content --}}
    <div class="relative z-10 flex flex-col items-center justify-between min-h-full py-6">
        
        {{-- Top: Wedding Title --}}
        <div class="text-center opacity-0" id="hero-subtitle-group">
            <span class="hero-date-badge" style="font-family: var(--theme-font-decorative);">The Wedding Of</span>
            <div class="section-line mt-2"></div>
        </div>

        {{-- Middle: Couple + Names --}}
        <div class="flex flex-col items-center space-y-4">
            {{-- Layer 6: Couple Illustration --}}
            <div class="opacity-0" id="hero-couple-img">
                <img src="{{ themeAsset('illustration.couple') }}" 
                     alt="Couple Illustration"
                     class="hero-couple-illustration" />
            </div>

            {{-- Names --}}
            <div class="text-center space-y-2 opacity-0" id="hero-names-group">
                <div class="hero-names-display">
                    {{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}
                </div>
                <div class="hero-ampersand">&</div>
                <div class="hero-names-display">
                    {{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}
                </div>
            </div>

            {{-- Date --}}
            <div class="text-center opacity-0 mt-4" id="hero-date-group">
                <p class="hero-date-badge">
                    {{ $invitationData['reception_date'] ? Carbon\Carbon::parse($invitationData['reception_date'])->translatedFormat('d F Y') : ($invitationData['akad_date'] ? Carbon\Carbon::parse($invitationData['akad_date'])->translatedFormat('d F Y') : '-') }}
                </p>
            </div>

            {{-- Guest Name --}}
            <div class="text-center space-y-2 mt-6 opacity-0" id="hero-guest-group">
                <p class="hero-guest-label">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <div class="hero-guest-name">
                    {{ $invitationData['recipient_name'] ?: 'Tamu Undangan' }}
                </div>
            </div>
        </div>

        {{-- Bottom: Open Button --}}
        <div class="text-center opacity-0" id="hero-btn-group">
            <button id="btn-open-invitation" class="hero-open-btn pulse-glow">
                {{-- Layer 7: Envelope Icon --}}
                <img src="{{ themeAsset('illustration.envelope') }}" 
                     alt="" aria-hidden="true"
                     class="hero-envelope" />
                Buka Undangan
            </button>
        </div>
    </div>
</div>

<script>
    // Generate floating floral petals
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('particles-container');
        if (container) {
            for (let i = 0; i < 12; i++) {
                const petal = document.createElement('div');
                petal.className = 'floral-petal';
                petal.style.left = (Math.random() * 100) + '%';
                petal.style.animationDelay = (Math.random() * 12) + 's';
                petal.style.animationDuration = (16 + Math.random() * 8) + 's';
                petal.style.opacity = (0.15 + Math.random() * 0.3).toString();
                petal.style.width = (10 + Math.random() * 8) + 'px';
                petal.style.height = petal.style.width;
                container.appendChild(petal);
            }
        }

        // Cinematic hero animation sequence using GSAP
        if (typeof gsap !== 'undefined') {
            const tl = gsap.timeline({ delay: 0.3 });
            
            // 1. Background fade in
            tl.to('#hero-bg', { 
                opacity: 1, 
                duration: 1.2, 
                ease: 'power2.out' 
            });

            // 2. Floral corners reveal
            tl.to('.hero-floral-corner', { 
                opacity: 0.85, 
                duration: 0.8, 
                ease: 'power2.out',
                stagger: 0.15
            }, '-=0.6');

            // 3. Subtitle
            tl.to('#hero-subtitle-group', { 
                opacity: 1, 
                y: 0, 
                duration: 0.6, 
                ease: 'power2.out' 
            }, '-=0.4');

            // 4. Couple illustration scale in
            tl.fromTo('#hero-couple-img', 
                { opacity: 0, scale: 0.8, y: 20 },
                { opacity: 1, scale: 1, y: 0, duration: 0.8, ease: 'back.out(1.4)' },
                '-=0.3'
            );

            // 5. Names stagger reveal
            tl.to('#hero-names-group', { 
                opacity: 1, 
                y: 0, 
                duration: 0.7, 
                ease: 'power2.out' 
            }, '-=0.3');

            // 6. Date reveal
            tl.to('#hero-date-group', { 
                opacity: 1, 
                duration: 0.5, 
                ease: 'power2.out' 
            }, '-=0.2');

            // 7. Guest name
            tl.to('#hero-guest-group', { 
                opacity: 1, 
                y: 0, 
                duration: 0.5, 
                ease: 'power2.out' 
            }, '-=0.2');

            // 8. Button pulse glow
            tl.to('#hero-btn-group', { 
                opacity: 1, 
                y: 0, 
                duration: 0.6, 
                ease: 'back.out(1.2)' 
            }, '-=0.1');
        } else {
            // Fallback: show everything
            document.querySelectorAll('#cover-overlay [id^="hero-"]').forEach(el => {
                el.style.opacity = '1';
            });
            document.querySelectorAll('.hero-floral-corner').forEach(el => {
                el.style.opacity = '0.85';
            });
        }
    });
</script>