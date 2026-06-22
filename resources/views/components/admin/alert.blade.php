@props([
    'type' => 'success',
    'message' => '',
])

@php
    $styles = [
        'success' => [
            'bg' => 'bg-emerald-50 dark:bg-emerald-950/20',
            'border' => 'border-emerald-200 dark:border-emerald-900/50',
            'text' => 'text-emerald-800 dark:text-emerald-300',
            'icon' => '<svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        ],
        'error' => [
            'bg' => 'bg-rose-50 dark:bg-rose-950/20',
            'border' => 'border-rose-200 dark:border-rose-900/50',
            'text' => 'text-rose-800 dark:text-rose-300',
            'icon' => '<svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        ],
        'warning' => [
            'bg' => 'bg-amber-50 dark:bg-amber-950/20',
            'border' => 'border-amber-200 dark:border-amber-900/50',
            'text' => 'text-amber-800 dark:text-amber-300',
            'icon' => '<svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>'
        ],
        'info' => [
            'bg' => 'bg-sky-50 dark:bg-sky-950/20',
            'border' => 'border-sky-200 dark:border-sky-900/50',
            'text' => 'text-sky-800 dark:text-sky-300',
            'icon' => '<svg class="h-5 w-5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        ],
    ];

    $style = $styles[$type] ?? $styles['success'];
@endphp

<div x-data="{ show: true }" 
     x-show="show" 
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="flex items-start gap-3 rounded-lg border p-4 {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }} shadow-sm"
     role="alert">
    <div class="flex-shrink-0">
        {!! $style['icon'] !!}
    </div>
    <div class="flex-1 text-sm font-medium">
        {{ $message ?: $slot }}
    </div>
    <button type="button" 
            @click="show = false" 
            class="flex-shrink-0 rounded-lg p-1 hover:bg-black/5 dark:hover:bg-white/5 transition-colors duration-150">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
