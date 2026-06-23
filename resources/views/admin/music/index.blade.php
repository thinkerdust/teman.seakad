@extends('admin.layouts.app')

@section('title', 'Kelola Musik Latar')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Kelola Musik Latar" :items="['Kelola Musik Latar' => '']" />

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6">
            <x-admin.alert type="success" message="{{ session('success') }}" />
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6">
            <x-admin.alert type="error" message="{{ session('error') }}" />
        </div>
    @endif

    <div class="space-y-6" x-data="{ activeAudioId: null }">
        <x-admin.card>
            <x-slot:header>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                            Perpustakaan Musik Latar
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Kelola lagu-lagu pernikahan premium yang dapat dipilih oleh pengguna untuk undangan digital mereka.
                        </p>
                    </div>
                    
                    @if(auth()->user()->hasPermission('music.create'))
                    <a 
                        href="{{ route('admin.music.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                    >
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Lagu
                    </a>
                    @endif
                </div>
            </x-slot:header>

            <!-- Filters & Search -->
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <!-- Search Form -->
                <form action="{{ route('admin.music.index') }}" method="GET" class="flex-grow max-w-md">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari judul lagu, artis, atau album..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        @if(request('search') || request('mood') || request('status'))
                            <a href="{{ route('admin.music.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-rose-500 hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filter Dropdowns -->
                <form id="filter-form" action="{{ route('admin.music.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    
                    <!-- Mood Filter -->
                    <div class="flex items-center gap-2">
                        <label for="mood-filter" class="text-sm font-medium text-slate-500 dark:text-slate-400">Mood:</label>
                        <select 
                            id="mood-filter" 
                            name="mood" 
                            onchange="document.getElementById('filter-form').submit()"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Semua</option>
                            @foreach($moods as $mood)
                                <option value="{{ $mood }}" {{ request('mood') == $mood ? 'selected' : '' }}>{{ $mood }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="flex items-center gap-2">
                        <label for="status-filter" class="text-sm font-medium text-slate-500 dark:text-slate-400">Status:</label>
                        <select 
                            id="status-filter" 
                            name="status" 
                            onchange="document.getElementById('filter-form').submit()"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Semua</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Table List -->
            <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-800">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/75 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-4">Lagu</th>
                            <th class="px-6 py-4">Mood / Genre</th>
                            <th class="px-6 py-4">Bahasa / Durasi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Audio Preview</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                        @forelse($musicList as $music)
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-900/10 transition">
                                <!-- Cover, Title, Artist -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 dark:border-slate-800 dark:bg-slate-950 flex-shrink-0">
                                            @if($music->cover)
                                                <img src="{{ asset($music->cover) }}" alt="Cover" class="h-full w-full object-cover" />
                                            @else
                                                <div class="h-full w-full flex items-center justify-center bg-indigo-50 text-indigo-550 dark:bg-indigo-950/40 dark:text-indigo-400">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 dark:text-white leading-snug">{{ $music->title }}</h4>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $music->artist }}</p>
                                            @if($music->album)
                                                <p class="text-xxs text-slate-400 dark:text-slate-500 italic mt-0.5">Album: {{ $music->album }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Mood & Genre -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-750 dark:bg-indigo-950/40 dark:text-indigo-400">
                                            {{ $music->mood }}
                                        </span>
                                        <div class="text-xs text-slate-400 dark:text-slate-500">{{ $music->genre }}</div>
                                    </div>
                                </td>

                                <!-- Language & Duration -->
                                <td class="px-6 py-4 whitespace-nowrap text-slate-650 dark:text-slate-400">
                                    <span class="text-xs font-medium">{{ $music->language ?: '-' }}</span>
                                    <div class="text-xxs text-slate-400 dark:text-slate-500">{{ $music->duration ?: '-' }}</div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($music->status === 'active')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-900/60 dark:text-slate-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Audio Player -->
                                <td class="px-6 py-4">
                                    @if($music->file)
                                        <div class="flex items-center gap-2" x-data="{ isPlaying: false, audio: null }">
                                            <button 
                                                type="button"
                                                @click="
                                                    if (!audio) {
                                                        audio = new Audio('{{ asset($music->file) }}');
                                                        audio.addEventListener('ended', () => { isPlaying = false; activeAudioId = null; });
                                                    }
                                                    if (isPlaying) {
                                                        audio.pause();
                                                        isPlaying = false;
                                                        activeAudioId = null;
                                                    } else {
                                                        if (activeAudioId && activeAudioId !== audio) {
                                                            // Dispatch event or logic to stop others is handled via global activeAudioId tracking
                                                        }
                                                        audio.play();
                                                        isPlaying = true;
                                                        activeAudioId = audio;
                                                    }
                                                "
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 hover:border-indigo-500 hover:text-indigo-600 shadow-sm transition dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400 dark:hover:text-indigo-400"
                                            >
                                                <svg x-show="!isPlaying" class="h-4.5 w-4.5 fill-current" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                                <svg x-show="isPlaying" class="h-4.5 w-4.5 fill-current" viewBox="0 0 24 24" x-cloak>
                                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                                </svg>
                                            </button>
                                            <span class="text-xxs text-slate-450 dark:text-slate-500 font-mono">Dengarkan</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">No File</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if(auth()->user()->hasPermission('music.update'))
                                        <a 
                                            href="{{ route('admin.music.edit', $music->id) }}"
                                            class="p-1.5 text-slate-400 hover:text-indigo-650 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                                            title="Ubah Musik"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        @endif

                                        @if(auth()->user()->hasPermission('music.delete'))
                                        <form 
                                            action="{{ route('admin.music.destroy', $music->id) }}" 
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus lagu ini dari perpustakaan? Semua undangan yang menggunakan lagu ini tidak akan lagi memiliki musik latar tersebut.');"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit"
                                                class="p-1.5 text-slate-400 hover:text-rose-650 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                                                title="Hapus Musik"
                                            >
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 bg-slate-50/50 dark:bg-slate-900/10 rounded-b-xl border border-dashed border-slate-100 dark:border-slate-800">
                                    <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                    <h4 class="mt-4 font-semibold text-slate-850 dark:text-white">Tidak Ada Lagu</h4>
                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Belum ada koleksi musik latar yang sesuai dengan pencarian Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $musicList->links() }}
            </div>
        </x-admin.card>
    </div>
@endsection
