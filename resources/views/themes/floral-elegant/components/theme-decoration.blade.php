@props([
    'type' => '',
    'class' => '',
    'style' => ''
])

@php
    $assetUrl = '';
    $defaultClasses = 'absolute pointer-events-none select-none z-0';
    $animationClass = '';

    switch ($type) {
        case 'corner-top-left':
            $assetUrl = themeAsset('floral.corner_left');
            $defaultClasses .= ' top-0 left-0 w-32 sm:w-40 md:w-48 origin-top-left';
            $animationClass = 'fade-in';
            break;
            
        case 'corner-top-right':
            $assetUrl = themeAsset('floral.corner_right');
            $defaultClasses .= ' top-0 right-0 w-32 sm:w-40 md:w-48 origin-top-right';
            $animationClass = 'fade-in';
            break;
            
        case 'corner-bottom-right':
            $assetUrl = themeAsset('floral.corner_bottom');
            $defaultClasses .= ' bottom-0 right-0 w-32 sm:w-36 md:w-44 origin-bottom-right';
            $animationClass = 'fade-in';
            break;
            
        case 'rose-01':
            $assetUrl = themeAsset('floral.rose_01');
            $defaultClasses .= ' w-10 sm:w-12 opacity-25';
            $animationClass = 'float';
            break;
            
        case 'rose-02':
            $assetUrl = themeAsset('floral.rose_02');
            $defaultClasses .= ' w-12 sm:w-16 opacity-30';
            $animationClass = 'float';
            break;
            
        case 'leaf-01':
            $assetUrl = themeAsset('floral.leaf_01');
            $defaultClasses .= ' w-8 sm:w-10 opacity-20';
            $animationClass = 'float';
            break;
            
        case 'leaf-02':
            $assetUrl = themeAsset('floral.leaf_02');
            $defaultClasses .= ' w-10 sm:w-12 opacity-20';
            $animationClass = 'float';
            break;
            
        case 'bouquet':
            $assetUrl = themeAsset('floral.bouquet');
            $defaultClasses .= ' w-36 sm:w-44 md:w-48 opacity-30';
            $animationClass = 'float';
            break;
            
        case 'divider':
            $assetUrl = themeAsset('ornament.divider');
            $defaultClasses .= ' w-48 sm:w-56 mx-auto';
            $animationClass = 'fade-up';
            break;
            
        case 'gold-line':
            $assetUrl = themeAsset('ornament.gold_line');
            $defaultClasses .= ' h-3 opacity-40';
            $animationClass = 'fade-in';
            break;
    }
@endphp

@if($type === 'sparkle')
    @include('themes.floral-elegant.components.ornament-overlay')
@elseif($assetUrl)
    <img src="{{ $assetUrl }}" 
         alt="" 
         aria-hidden="true" 
         class="{{ $defaultClasses }} {{ $animationClass }} {{ $class }}" 
         style="{{ $style }}" 
         @if($animationClass && $animationClass !== 'float') data-animation @endif
    />
@endif
