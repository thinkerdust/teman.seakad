@props([
    'pageTitle' => '',
    'items' => []
])

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">
        {{ $pageTitle }}
    </h2>

    <nav>
        <ol class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
            <li>
                <a class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400" href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
            </li>
            @if(count($items) > 0)
                <li class="font-medium text-slate-400 dark:text-slate-600">/</li>
            @endif
            @foreach($items as $label => $url)
                @if(!$loop->last)
                    <li>
                        @if($url)
                            <a class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400" href="{{ $url }}">
                                {{ $label }}
                            </a>
                        @else
                            <span class="font-medium">{{ $label }}</span>
                        @endif
                    </li>
                    <li class="font-medium text-slate-400 dark:text-slate-600">/</li>
                @else
                    <li class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $label }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>
