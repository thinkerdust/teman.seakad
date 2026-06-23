<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
    
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
<body class="antialiased min-h-screen flex flex-col bg-[#fdfbf7] text-[#2d2d2d] dark:bg-zinc-950 dark:text-zinc-200">
    <!-- Navbar -->
    @include('landing.components.navbar')

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('landing.components.footer')

    @stack('scripts')
</body>
</html>
