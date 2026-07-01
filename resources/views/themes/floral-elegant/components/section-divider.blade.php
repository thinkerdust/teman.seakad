@props([
    'type' => 'flower', // 'flower', 'gold', 'both'
    'class' => ''
])

<div class="flex flex-col items-center justify-center py-8 pointer-events-none select-none {{ $class }}">
    @if($type === 'flower' || $type === 'both')
        <img src="{{ themeAsset('ornament.divider') }}" 
             alt="" 
             aria-hidden="true" 
             class="w-48 sm:w-56 opacity-60 fade-up float" 
             data-animation 
        />
    @endif
    
    @if($type === 'both')
        <div class="h-2"></div>
    @endif
    
    @if($type === 'gold' || $type === 'both')
        <div class="flex items-center justify-center w-full max-w-xs px-6">
            <div class="h-[1px] flex-grow opacity-20" style="background: var(--theme-secondary);"></div>
            <img src="{{ themeAsset('ornament.gold_line') }}" 
                 alt="" 
                 aria-hidden="true" 
                 class="h-2 mx-4 opacity-50 fade-in" 
                 data-animation 
            />
            <div class="h-[1px] flex-grow opacity-20" style="background: var(--theme-secondary);"></div>
        </div>
    @endif
</div>
