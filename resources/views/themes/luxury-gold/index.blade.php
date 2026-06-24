<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title>{{ $invitationData['title'] }}</title>
    <meta name="description" content="Undangan Pernikahan {{ $invitationData['groom_name'] }} & {{ $invitationData['bride_name'] }}. Temukan detail acara, lokasi, dan RSVP di sini.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $invitationData['title'] }}">
    <meta property="og:description" content="Undangan Pernikahan {{ $invitationData['groom_name'] }} & {{ $invitationData['bride_name'] }}.">
    @if($invitation->theme && $invitation->theme->thumbnail)
        <meta property="og:image" content="{{ asset($invitation->theme->thumbnail) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cinzel:wght@400..900&family=Great+Vibes&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- GSAP & Alpine JS -->
    <script src="{{ asset('assets/vendor/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/gsap/ScrollTrigger.min.js') }}"></script>
    
    <!-- Theme Isolated CSS -->
    <link rel="stylesheet" href="{{ asset('themes/' . $invitation->theme->folder . '/css/style.css') }}">
    
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        window.invitationData = @json($invitationData);
    </script>
</head>
<body class="antialiased min-h-screen relative overflow-x-hidden" x-data="{ opened: false }">

    <!-- Cover / Landing Overlay -->
    @include('themes.' . $invitation->theme->folder . '.components.hero', [
        'invitationData' => $invitationData,
        'invitation' => $invitation
    ])

    <!-- Main Content (revealed after clicking Buka Undangan) -->
    <div id="main-content" class="hidden opacity-0 w-full max-w-md mx-auto min-h-screen shadow-2xl relative z-10 theme-{{ $invitation->theme->folder }}">
        
        <!-- Couple Section -->
        @include('themes.' . $invitation->theme->folder . '.components.couple', [
            'invitationData' => $invitationData,
            'invitation' => $invitation
        ])

        <!-- Countdown Section -->
        @if($themeConfig['features']['countdown'] ?? true)
            @include('themes.' . $invitation->theme->folder . '.components.countdown', [
                'invitationData' => $invitationData,
                'invitation' => $invitation
            ])
        @endif

        <!-- Events Section -->
        @include('themes.' . $invitation->theme->folder . '.components.event', [
            'invitationData' => $invitationData,
            'invitation' => $invitation
        ])

        <!-- Story Section -->
        @if(($themeConfig['features']['stories'] ?? true) && count($invitationData['story']) > 0)
            @include('themes.' . $invitation->theme->folder . '.components.story', [
                'invitationData' => $invitationData,
                'invitation' => $invitation
            ])
        @endif

        <!-- Gallery Section -->
        @if(($themeConfig['features']['gallery'] ?? true) && count($invitationData['gallery']) > 0)
            @include('themes.' . $invitation->theme->folder . '.components.gallery', [
                'invitationData' => $invitationData,
                'invitation' => $invitation
            ])
        @endif

        <!-- Gift Section -->
        @if($themeConfig['features']['gift'] ?? true)
            @include('themes.' . $invitation->theme->folder . '.components.gift', [
                'invitationData' => $invitationData,
                'invitation' => $invitation
            ])
        @endif

        <!-- RSVP Section -->
        @if($themeConfig['features']['rsvp'] ?? true)
            @include('themes.' . $invitation->theme->folder . '.components.rsvp', [
                'invitationData' => $invitationData,
                'invitation' => $invitation
            ])
        @endif

        <!-- Guest Wish Section -->
        @include('themes.' . $invitation->theme->folder . '.components.guest-wish', [
            'invitationData' => $invitationData,
            'invitation' => $invitation
        ])

        <!-- Footer Section -->
        @include('themes.' . $invitation->theme->folder . '.components.footer', [
            'invitationData' => $invitationData,
            'invitation' => $invitation
        ])
    </div>

    <!-- Background Music Player Component -->
    @if(($themeConfig['features']['music'] ?? true) && !empty($invitationData['music']['file']))
        @include('themes.' . $invitation->theme->folder . '.components.music', [
            'music' => $invitationData['music']
        ])
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            const btnOpen = document.getElementById('btn-open-invitation');
            const coverOverlay = document.getElementById('cover-overlay');
            const mainContent = document.getElementById('main-content');
            const audioPlayer = document.getElementById('bg-music-player');

            if (btnOpen) {
                btnOpen.addEventListener('click', () => {
                    // Start playing music
                    if (audioPlayer) {
                        audioPlayer.play().catch(err => console.log('Autoplay blocked or failed:', err));
                    }

                    // Dispatch custom event for Alpine and music control
                    window.dispatchEvent(new CustomEvent('invitation-opened'));

                    // GSAP transition
                    gsap.timeline()
                        .to(btnOpen, { duration: 0.3, scale: 0, opacity: 0, ease: 'power2.in' })
                        .to(coverOverlay, { duration: 0.8, yPercent: -100, ease: 'power3.inOut' })
                        .set(coverOverlay, { display: 'none' })
                        .set(mainContent, { display: 'block' })
                        .to(mainContent, { duration: 0.5, opacity: 1, ease: 'power2.out' })
                        .call(() => {
                            // Enable body overflow
                            document.body.classList.remove('overflow-hidden');
                            
                            // Initialize ScrollTrigger animations
                            initAnimations();
                        });
                });
            }

            function initAnimations() {
                // Reveal section animations
                const sections = document.querySelectorAll('section[data-animation="fade-up"]');
                sections.forEach(section => {
                    gsap.from(section, {
                        scrollTrigger: {
                            trigger: section,
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        },
                        y: 50,
                        opacity: 0,
                        duration: 1,
                        ease: 'power3.out'
                    });
                });
            }
        });
    </script>
</body>
</html>