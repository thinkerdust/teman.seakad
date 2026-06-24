<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Digital Wedding Invitation - Create Beautiful Online Invitation | Teman Seakad')</title>
    <meta name="description" content="@yield('meta_description', 'Buat undangan pernikahan digital yang elegan, mewah, interaktif, dan mudah dibagikan. Pilih tema premium, kelola RSVP tamu, bagikan momen bahagia Anda secara instan.')">
    <meta name="keywords" content="undangan pernikahan digital, digital wedding invitation, rsvp online, website pernikahan, teman seakad, undangan pernikahan murah, e-invitation">
    <meta name="author" content="Teman Seakad">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Digital Wedding Invitation - Create Beautiful Online Invitation')">
    <meta property="og:description" content="@yield('og_description', 'Buat undangan pernikahan digital yang elegan, mewah, interaktif, dan mudah dibagikan di Teman Seakad.')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/og-image.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', 'Digital Wedding Invitation - Create Beautiful Online Invitation')">
    <meta property="twitter:description" content="@yield('twitter_description', 'Buat undangan pernikahan digital yang elegan dan mudah dibagikan.')">
    <meta property="twitter:image" content="@yield('twitter_image', asset('assets/images/og-image.jpg'))">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    <script>
        // Sinkronkan tema gelap dari localStorage sebelum page load untuk menghindari flash
        try {
            const darkMode = localStorage.getItem('_x_darkMode');
            if (darkMode === 'true' || (darkMode === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            console.error('Gagal memuat tema gelap', e);
        }
    </script>
    @vite(['resources/css/landing.css', 'resources/js/landing/app.js'])
    
    @stack('styles')

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WeddingService",
        "name": "Teman Seakad",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('assets/images/logo.png') }}",
        "description": "Layanan pembuatan undangan pernikahan digital premium, interaktif, dan elegan di Indonesia.",
        "address": {
            "@@type": "PostalAddress",
            "addressCountry": "ID"
        },
        "priceRange": "$$"
    }
    </script>
</head>
<body class="antialiased min-h-screen flex flex-col bg-[#fdfbf7] text-[#2d2d2d] dark:bg-zinc-950 dark:text-zinc-200" id="page-wrapper">
    <!-- Navbar -->
    @include('landing.components.navbar')

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('landing.components.footer')

    <!-- Floating WhatsApp Order Button -->
    <a 
        href="{{ $whatsappOrderUrl ?? '#' }}" 
        target="_blank" 
        rel="noopener noreferrer"
        class="fixed bottom-6 right-6 z-50 group flex items-center gap-3"
        aria-label="Pesan Undangan via WhatsApp"
    >
        {{-- Tooltip label (tampil saat hover) --}}
        <span class="hidden sm:block opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300 bg-zinc-900/90 dark:bg-zinc-800 text-white text-xs font-semibold px-4 py-2 rounded-full shadow-lg whitespace-nowrap pointer-events-none">
            Pesan Undangan
        </span>
        {{-- FAB icon --}}
        <span class="relative flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white shadow-xl shadow-green-500/30 hover:shadow-2xl hover:shadow-green-500/50 hover:scale-110 transition-all duration-300">
            {{-- Pulse ring --}}
            <span class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-20"></span>
            <svg class="h-7 w-7 relative z-10" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </span>
    </a>

    @stack('scripts')
</body>
</html>
