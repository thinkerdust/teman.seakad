{{-- 
    Footer — Premium Closing Experience (Phase 2.1)
    
    Features:
    - Reusable flower divider & gold line ornaments
    - Thank you message
    - With Love text
    - Bride & Groom names in accent font
    - Bottom floral bouquet ornament
    - Fade closing animation
--}}

@php
    $themeFolder = $invitation->theme->folder;
@endphp

<footer class="footer-closing relative overflow-hidden" {!! themeAnimation('footer') !!}>
    {{-- Sparkle Overlay --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'sparkle'])

    <div class="relative z-10 space-y-6">
        {{-- Flower Divider --}}
        @include('themes.' . $themeFolder . '.components.section-divider', ['type' => 'flower', 'class' => 'py-2'])

        {{-- Thank You Message --}}
        <div class="space-y-2 fade-up" data-animation style="animation-delay: 0.15s;">
            <p class="text-xs opacity-65 leading-relaxed max-w-xs mx-auto" style="color: var(--theme-text);">
                Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kami.
            </p>
        </div>

        {{-- Gold Line --}}
        @include('themes.' . $themeFolder . '.components.section-divider', ['type' => 'gold', 'class' => 'py-2'])

        {{-- With Love --}}
        <div class="space-y-3 fade-up" data-animation style="animation-delay: 0.35s;">
            <p class="footer-thankyou">Kami yang berbahagia,</p>
            <p class="footer-withlove">With Love</p>
            <div class="footer-names">
                {{ $invitationData['groom_name'] }} & {{ $invitationData['bride_name'] }}
            </div>
        </div>

        {{-- Floral Bottom Bouquet Decoration --}}
        <div class="flex justify-center pt-4 fade-in" data-animation style="animation-delay: 0.45s;">
            @include('themes.' . $themeFolder . '.components.theme-decoration', [
                'type' => 'bouquet',
                'class' => 'relative block opacity-40',
                'style' => 'position: relative; width: 8rem; height: auto;'
            ])
        </div>

        {{-- Credit --}}
        <div class="pt-6" style="border-top: 1px solid var(--theme-secondary);">
            <a href="https://teman-seakad.com" target="_blank" class="footer-credit">
                Created with ♥ by Teman Seakad
            </a>
        </div>
    </div>
</footer>