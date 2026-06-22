@props([
    'title' => null,
])

<div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
    @if($title || isset($header))
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="font-semibold text-slate-800 dark:text-white">
                    {{ $title }}
                </h3>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/50">
            {{ $footer }}
        </div>
    @endif
</div>
