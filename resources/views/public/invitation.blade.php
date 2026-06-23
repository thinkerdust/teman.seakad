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

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $invitationData['title'] }}">
    <meta property="twitter:description" content="Undangan Pernikahan {{ $invitationData['groom_name'] }} & {{ $invitationData['bride_name'] }}.">
    @if($invitation->theme && $invitation->theme->thumbnail)
        <meta property="twitter:image" content="{{ asset($invitation->theme->thumbnail) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cinzel:wght@400..900&family=Great+Vibes&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Base page transitions/cloaks if any */
        [x-cloak] {
            display: none !important;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #faf9f6;
            font-family: 'Instrument Sans', sans-serif;
            color: #1e293b;
        }
        .font-serif-elegant {
            font-family: 'Playfair Display', Georgia, serif;
        }
    </style>
</head>
<body class="overflow-hidden">
    <!-- Premium Cover Overlay (GSAP animated) -->
    <div id="cover-overlay" class="fixed inset-0 z-9999 flex flex-col items-center justify-between bg-gradient-to-b from-slate-900 via-slate-800 to-slate-950 text-white p-8 text-center">
        <!-- Top decorative element -->
        <div class="mt-8">
            <span class="text-xs uppercase tracking-widest text-indigo-400 font-semibold">Undangan Pernikahan</span>
            <div class="h-0.5 w-12 bg-indigo-400 mx-auto mt-2"></div>
        </div>

        <!-- Middle content -->
        <div class="space-y-6">
            <h1 class="font-serif-elegant text-4xl sm:text-5xl md:text-6xl font-bold tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-amber-100 to-amber-250">
                {{ $invitationData['groom_name'] }} & {{ $invitationData['bride_name'] }}
            </h1>
            
            <div class="space-y-2 mt-8">
                <p class="text-xs text-slate-400 uppercase tracking-wider">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <div class="text-lg font-semibold text-amber-100 py-1.5 px-4 bg-white/5 rounded-full inline-block backdrop-blur-sm border border-white/10">
                    {{ $invitationData['recipient_name'] ?: 'Tamu Undangan' }}
                </div>
            </div>
        </div>

        <!-- Bottom action -->
        <div class="mb-12">
            <button 
                id="btn-open-invitation"
                class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-amber-300 to-amber-400 text-slate-900 font-bold text-sm tracking-wide shadow-lg shadow-amber-400/20 hover:scale-105 transition duration-300"
            >
                <svg class="h-4.5 w-4.5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
                Buka Undangan
            </button>
        </div>
    </div>

    <!-- App Container where Vue will mount -->
    <div id="app">
        <div class="flex h-screen items-center justify-center">
            <div class="text-center">
                <!-- Loading placeholder state before Vue mounts -->
                <svg class="mx-auto h-10 w-10 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-sm font-semibold text-slate-500">Memuat Undangan...</p>
            </div>
        </div>
    </div>

    <!-- Pass Laravel data to Vue -->
    <script>
        window.invitationData = @json($invitationData);
    </script>
</body>
</html>
