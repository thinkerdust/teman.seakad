{{-- 
    Couple Section — Premium Mempelai Introduction (Phase 2.1)
    
    Structure:
    - Inline Hero (names, date, venue)
    - Floral Divider
    - Groom Details (photo with arch frame, name, story)
    - Heart Divider with gold line ornament
    - Bride Details (photo with arch frame, name, story)
--}}

@php
    $themeFolder = $invitation->theme->folder;
@endphp

{{-- Inline Hero Section --}}
<div class="inline-hero-section relative">
    <div class="hero-bg-muted" style="background-image: url('{{ themeAsset('background.watercolor') }}');"></div>
    
    {{-- Sparkle overlay --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'sparkle'])

    <div class="relative z-10 max-w-2xl mx-auto flex flex-col items-center">
        <span class="section-subtitle mb-4 fade-up" data-animation>
            Undangan Pernikahan
        </span>
        
        <div class="text-center my-2 sm:my-4 fade-up" data-animation style="animation-delay: 0.15s;">
            <h1 class="hero-names-display">
                {{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }} & {{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}
            </h1>
        </div>
        
        <div class="mt-4 space-y-3 w-full text-center fade-up" data-animation style="animation-delay: 0.3s;">
            <div class="section-line"></div>
            <p class="hero-date-badge text-sm sm:text-base">
                {{ $invitationData['reception_date'] ? Carbon\Carbon::parse($invitationData['reception_date'])->translatedFormat('d F Y') : ( $invitationData['akad_date'] ? Carbon\Carbon::parse($invitationData['akad_date'])->translatedFormat('d F Y') : '-' ) }}
            </p>
            <p class="text-xs sm:text-sm opacity-75 max-w-md mx-auto leading-relaxed" style="color: var(--theme-text);">
                {{ $invitationData['venue'] }}
            </p>
            @if($invitationData['description'])
                <p class="text-xs italic opacity-65 max-w-md mx-auto mt-6 px-4 leading-relaxed" style="color: var(--theme-text);">
                    "{{ $invitationData['description'] }}"
                </p>
            @endif
        </div>
    </div>
</div>

{{-- Floral Divider --}}
@include('themes.' . $themeFolder . '.components.section-divider', ['type' => 'flower'])

{{-- Mempelai Section --}}
<section class="py-12 px-6 text-center space-y-10 relative overflow-hidden" style="border-bottom: 1px solid var(--theme-secondary);" {!! themeAnimation('couple') !!}>
    {{-- Floating Floral Decorations for Storytelling --}}
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'rose-01', 'class' => 'left-2 top-20 opacity-20'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'leaf-01', 'class' => 'right-2 top-40 opacity-15'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'rose-02', 'class' => 'left-4 bottom-40 opacity-20'])
    @include('themes.' . $themeFolder . '.components.theme-decoration', ['type' => 'leaf-02', 'class' => 'right-4 bottom-20 opacity-15'])

    {{-- Section Header --}}
    <div class="section-header fade-up relative z-10" data-animation>
        <h2 class="section-title" style="font-family: var(--theme-font-heading);">Mempelai</h2>
        <div class="section-line"></div>
        <p class="text-xs opacity-65 max-w-xs mx-auto leading-relaxed mt-3" style="color: var(--theme-text);">
            Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah, perkenankanlah kami menyatukan dua hati dalam ikatan pernikahan yang suci.
        </p>
    </div>

    {{-- Groom --}}
    <div class="space-y-3 fade-up relative z-10" data-animation style="animation-delay: 0.15s;">
        <div class="photo-frame arch scale-in" data-animation style="animation-delay: 0.3s;">
            <img src="{{ themeAsset('frame.arch') }}" alt="" class="frame-border" aria-hidden="true" />
            @if(!empty($invitationData['groom_photo']))
                <img src="{{ $invitationData['groom_photo'] }}" 
                     alt="{{ $invitationData['groom_nickname'] ?? $invitationData['groom_name'] }}"
                     class="frame-photo" />
            @else
                <div class="couple-initial flex items-center justify-center w-full h-full bg-slate-50/50">
                    {{ substr($invitationData['groom_nickname'] ?? $invitationData['groom_name'], 0, 1) }}
                </div>
            @endif
        </div>
        <div class="couple-name mt-4">{{ $invitationData['groom_name'] }}</div>
        <p class="couple-subtitle">Putra dari Bapak & Ibu Terbaik</p>
        @if(!empty($invitationData['groom_description']))
            <p class="couple-story-text">{{ $invitationData['groom_description'] }}</p>
        @endif
    </div>

    {{-- Heart Divider --}}
    <div class="couple-heart-divider fade-in relative z-10" data-animation style="animation-delay: 0.2s;">
        <div class="line"></div>
        <img src="{{ themeAsset('ornament.gold_line') }}" alt="" class="h-3 opacity-40" aria-hidden="true" />
        <span class="heart-icon">♡</span>
        <img src="{{ themeAsset('ornament.gold_line') }}" alt="" class="h-3 opacity-40" aria-hidden="true" />
        <div class="line"></div>
    </div>

    {{-- Bride --}}
    <div class="space-y-3 fade-up relative z-10" data-animation style="animation-delay: 0.3s;">
        <div class="photo-frame arch scale-in" data-animation style="animation-delay: 0.45s;">
            <img src="{{ themeAsset('frame.arch') }}" alt="" class="frame-border" aria-hidden="true" />
            @if(!empty($invitationData['bride_photo']))
                <img src="{{ $invitationData['bride_photo'] }}" 
                     alt="{{ $invitationData['bride_nickname'] ?? $invitationData['bride_name'] }}"
                     class="frame-photo" />
            @else
                <div class="couple-initial flex items-center justify-center w-full h-full bg-slate-50/50">
                    {{ substr($invitationData['bride_nickname'] ?? $invitationData['bride_name'], 0, 1) }}
                </div>
            @endif
        </div>
        <div class="couple-name mt-4">{{ $invitationData['bride_name'] }}</div>
        <p class="couple-subtitle">Putri dari Bapak & Ibu Terbaik</p>
        @if(!empty($invitationData['bride_description']))
            <p class="couple-story-text">{{ $invitationData['bride_description'] }}</p>
        @endif
    </div>
</section>