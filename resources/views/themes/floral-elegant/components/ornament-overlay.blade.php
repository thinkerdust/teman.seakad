{{-- 
    Ornament Overlay — Global decorative animation layer (Pure CSS)
    
    Menampilkan partikel berkilau dan titik bercahaya lembut 
    dengan gerakan acak tanpa dependensi JavaScript.
    
    Usage: @include('themes.floral-elegant.components.ornament-overlay')
--}}

<div class="sparkle-overlay" aria-hidden="true">
    {{-- Soft Glowing Dots — Static positions with glow animation --}}
    <div class="sparkle-particle" style="width: 4px; height: 4px; top: 12%; left: 18%; --sparkle-duration: 3.5s; --sparkle-delay: 0s;"></div>
    <div class="sparkle-particle" style="width: 6px; height: 6px; top: 28%; left: 72%; --sparkle-duration: 4.2s; --sparkle-delay: 0.8s;"></div>
    <div class="sparkle-particle" style="width: 3px; height: 3px; top: 45%; left: 35%; --sparkle-duration: 5s; --sparkle-delay: 1.5s;"></div>
    <div class="sparkle-particle" style="width: 5px; height: 5px; top: 62%; left: 85%; --sparkle-duration: 3.8s; --sparkle-delay: 2s;"></div>
    <div class="sparkle-particle" style="width: 4px; height: 4px; top: 78%; left: 22%; --sparkle-duration: 4.5s; --sparkle-delay: 0.5s;"></div>
    <div class="sparkle-particle" style="width: 3px; height: 3px; top: 88%; left: 55%; --sparkle-duration: 5.2s; --sparkle-delay: 1.2s;"></div>
    <div class="sparkle-particle" style="width: 5px; height: 5px; top: 8%; left: 48%; --sparkle-duration: 3.2s; --sparkle-delay: 2.5s;"></div>
    <div class="sparkle-particle" style="width: 4px; height: 4px; top: 55%; left: 8%; --sparkle-duration: 4.8s; --sparkle-delay: 1.8s;"></div>

    {{-- Drifting Sparkles — Floating movement --}}
    <div class="sparkle-particle drift" style="width: 3px; height: 3px; top: 20%; left: 30%; --sparkle-duration: 7s; --sparkle-delay: 0s; --drift-x: 25px; --drift-y: -60px;"></div>
    <div class="sparkle-particle drift" style="width: 4px; height: 4px; top: 50%; left: 65%; --sparkle-duration: 8s; --sparkle-delay: 1.5s; --drift-x: -20px; --drift-y: -70px;"></div>
    <div class="sparkle-particle drift" style="width: 3px; height: 3px; top: 70%; left: 15%; --sparkle-duration: 6.5s; --sparkle-delay: 3s; --drift-x: 35px; --drift-y: -50px;"></div>
    <div class="sparkle-particle drift" style="width: 5px; height: 5px; top: 35%; left: 80%; --sparkle-duration: 9s; --sparkle-delay: 2s; --drift-x: -15px; --drift-y: -90px;"></div>
    <div class="sparkle-particle drift" style="width: 3px; height: 3px; top: 85%; left: 45%; --sparkle-duration: 7.5s; --sparkle-delay: 0.5s; --drift-x: 20px; --drift-y: -65px;"></div>
</div>
