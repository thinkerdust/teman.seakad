@props([
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendUp' => true,
])

<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
                {{ $title }}
            </span>
            <h4 class="mt-2 text-3xl font-bold text-slate-800 dark:text-white">
                {{ $value }}
            </h4>
        </div>

        @if($icon || isset($iconSlot))
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                @if(isset($iconSlot))
                    {{ $iconSlot }}
                @else
                    {!! $icon !!}
                @endif
            </div>
        @endif
    </div>

    @if($trend)
        <div class="mt-4 flex items-center gap-1 text-sm">
            <span class="flex items-center font-medium {{ $trendUp ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                @if($trendUp)
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                @else
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                    </svg>
                @endif
                {{ $trend }}
            </span>
            <span class="text-slate-400 dark:text-slate-500">vs last month</span>
        </div>
    @endif
</div>
