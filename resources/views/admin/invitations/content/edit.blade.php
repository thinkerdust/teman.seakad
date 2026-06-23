@extends('admin.layouts.app')

@section('title', 'Kelola Konten Undangan')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Kelola Konten" :items="['Daftar Undangan' => route('admin.invitations.index'), 'Kelola Konten' => '']" />

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

    @if($errors->any())
        <div class="mb-6">
            <x-admin.alert type="error">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-admin.alert>
        </div>
    @endif

    @php
        $storiesData = $invitation->stories->sortBy('sort')->map(function($s) {
            return [
                'id' => $s->id,
                'title' => $s->title,
                'date' => $s->date,
                'description' => $s->description,
            ];
        })->values();

        $eventsData = $invitation->events->sortBy('date')->map(function($e) {
            return [
                'id' => $e->id,
                'name' => $e->name,
                'date' => $e->date ? $e->date->format('Y-m-d') : '',
                'time' => $e->time,
                'location' => $e->location,
            ];
        })->values();
    @endphp

    <div 
        x-data="invitationContentManager({
            stories: {{ json_encode($storiesData) }},
            events: {{ json_encode($eventsData) }},
            allMusic: {{ json_encode($musicLibrary) }},
            themeSlug: '{{ $invitation->theme?->slug ?: $invitation->theme?->folder }}',
            weddingMood: '{{ $invitation->wedding_mood ?: '' }}',
            selectedMusicId: {{ $invitation->music->first() ? $invitation->music->first()->id : 'null' }}
        })"
        class="space-y-6"
    >
        <!-- Header Info Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        {{ $invitation->title }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Kelola galeri foto, cerita cinta, acara tambahan, dan musik latar untuk undangan digital 
                        <code class="text-xs font-mono bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-indigo-650 dark:text-indigo-400">/{{ $invitation->slug }}</code>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('admin.invitations.index') }}" 
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Kembali
                    </a>
                    @if($invitation->status === 'published')
                        <a 
                            href="{{ url('/' . $invitation->slug) }}" 
                            target="_blank"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Lihat Undangan
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-slate-200 dark:border-slate-800">
            <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                <!-- Tab Galeri -->
                <button 
                    @click="activeTab = 'gallery'"
                    :class="activeTab === 'gallery' ? 'border-indigo-500 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-450 dark:hover:text-slate-200'"
                    class="group inline-flex items-center border-b-2 py-4 px-1 text-sm font-semibold transition whitespace-nowrap"
                >
                    <svg :class="activeTab === 'gallery' ? 'text-indigo-500' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500'" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Galeri Foto
                </button>

                <!-- Tab Cerita Cinta -->
                <button 
                    @click="activeTab = 'stories'"
                    :class="activeTab === 'stories' ? 'border-indigo-500 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-450 dark:hover:text-slate-200'"
                    class="group inline-flex items-center border-b-2 py-4 px-1 text-sm font-semibold transition whitespace-nowrap"
                >
                    <svg :class="activeTab === 'stories' ? 'text-indigo-500' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500'" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Cerita Cinta
                </button>

                <!-- Tab Susunan Acara -->
                <button 
                    @click="activeTab = 'events'"
                    :class="activeTab === 'events' ? 'border-indigo-500 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-450 dark:hover:text-slate-200'"
                    class="group inline-flex items-center border-b-2 py-4 px-1 text-sm font-semibold transition whitespace-nowrap"
                >
                    <svg :class="activeTab === 'events' ? 'text-indigo-500' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500'" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Susunan Acara
                </button>

                <!-- Tab Musik Latar -->
                <button 
                    @click="activeTab = 'music'"
                    :class="activeTab === 'music' ? 'border-indigo-500 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-450 dark:hover:text-slate-200'"
                    class="group inline-flex items-center border-b-2 py-4 px-1 text-sm font-semibold transition whitespace-nowrap"
                >
                    <svg :class="activeTab === 'music' ? 'text-indigo-500' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500'" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    Musik Latar
                </button>
            </nav>
        </div>

        <!-- Tab Contents Wrapper -->
        <div class="mt-4">
            <!-- TAB GALERI -->
            <div x-show="activeTab === 'gallery'" x-cloak class="space-y-6">
                <x-admin.card title="Unggah Foto Galeri">
                    <form 
                        action="{{ route('admin.invitations.content.gallery', $invitation->id) }}" 
                        method="POST" 
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @csrf
                        <input type="hidden" name="action" value="upload">
                        
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                            <div class="flex-grow">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih File Gambar</label>
                                <div class="relative flex items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50/50 p-6 text-center hover:bg-slate-55 transition dark:border-slate-700 dark:bg-slate-900/50">
                                    <input 
                                        type="file" 
                                        name="image" 
                                        accept="image/*"
                                        required
                                        class="absolute inset-0 cursor-pointer opacity-0"
                                    />
                                    <div class="space-y-1.5">
                                        <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <div class="text-xs text-slate-550 dark:text-slate-400">
                                            <span class="font-semibold text-indigo-600 hover:text-indigo-500">Pilih file</span> atau seret foto ke sini
                                        </div>
                                        <p class="text-xxs text-slate-400">PNG, JPG, JPEG, GIF hingga 20MB</p>
                                    </div>
                                </div>
                            </div>
                            <button 
                                type="submit" 
                                class="rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150 whitespace-nowrap self-stretch sm:self-auto sm:h-[58px] flex items-center justify-center"
                            >
                                Unggah Foto
                            </button>
                        </div>
                    </form>
                </x-admin.card>

                <x-admin.card title="Koleksi Galeri Foto">
                    @if($invitation->galleries->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="rounded-full bg-slate-100 p-3 dark:bg-slate-800">
                                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 class="mt-4 font-semibold text-slate-800 dark:text-white">Galeri Foto Kosong</h4>
                            <p class="mt-1 text-sm text-slate-400 dark:text-slate-500 max-w-xs">
                                Unggah foto pertama untuk menampilkan koleksi dokumentasi pada undangan pernikahan digital.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            @foreach($invitation->galleries as $gallery)
                                <div class="group relative aspect-square overflow-hidden rounded-xl border border-slate-200 bg-slate-100 dark:border-slate-800 dark:bg-slate-950">
                                    <img 
                                        src="{{ $gallery->image }}" 
                                        alt="Gallery Photo" 
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    />
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition duration-200 group-hover:opacity-100">
                                        <form 
                                            action="{{ route('admin.invitations.content.gallery', $invitation->id) }}" 
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini dari galeri?');"
                                        >
                                            @csrf
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">
                                            <button 
                                                type="submit" 
                                                class="rounded-lg bg-rose-650 p-2 text-white shadow-md hover:bg-rose-600 transition"
                                                title="Hapus Foto"
                                            >
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-admin.card>
            </div>

            <!-- TAB CERITA CINTA -->
            <div x-show="activeTab === 'stories'" x-cloak>
                <form action="{{ route('admin.invitations.content.story', $invitation->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="delete_story_ids" :value="deletedStoryIds.join(',')">
                    
                    <x-admin.card>
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-slate-800 dark:text-white">
                                    Milestones Perjalanan Cerita Cinta
                                </h3>
                                <button 
                                    type="button" 
                                    @click="addStory()"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-750 hover:bg-indigo-100 transition dark:bg-indigo-950/30 dark:text-indigo-400 dark:hover:bg-indigo-900/40"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Milestone
                                </button>
                            </div>
                        </x-slot:header>

                        <!-- Alpine Dynamic Stories List -->
                        <div class="space-y-6">
                            <template x-for="(story, index) in stories" :key="index">
                                <div class="relative rounded-xl border border-slate-150 bg-slate-50/30 p-5 dark:border-slate-800 dark:bg-slate-900/30 space-y-4">
                                    <!-- Delete Button -->
                                    <button 
                                        type="button" 
                                        @click="removeStory(index)"
                                        class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition"
                                        title="Hapus Milestone"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                    <!-- Story ID -->
                                    <input type="hidden" :name="`stories[${index}][id]`" x-model="story.id">

                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 pr-6">
                                        <!-- Title -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-650 dark:text-slate-350 mb-1">Judul Cerita <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="text" 
                                                :name="`stories[${index}][title]`" 
                                                x-model="story.title"
                                                required
                                                placeholder="Contoh: Pertama Bertemu"
                                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                                            />
                                        </div>

                                        <!-- Date -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-650 dark:text-slate-350 mb-1">Tanggal / Waktu <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="text" 
                                                :name="`stories[${index}][date]`" 
                                                x-model="story.date"
                                                required
                                                placeholder="Contoh: 12 Desember 2023"
                                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                                            />
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="pr-6">
                                        <label class="block text-xs font-bold text-slate-650 dark:text-slate-350 mb-1">Isi Cerita <span class="text-rose-500">*</span></label>
                                        <textarea 
                                            :name="`stories[${index}][description]`" 
                                            x-model="story.description"
                                            rows="3"
                                            required
                                            placeholder="Tulis detail singkat cerita cinta Anda di milestone ini..."
                                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                                        ></textarea>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty State inside tab -->
                            <div x-show="stories.length === 0" class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="rounded-full bg-slate-100 p-3 dark:bg-slate-800">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <h4 class="mt-3 font-semibold text-slate-800 dark:text-white text-sm">Belum Ada Cerita Cinta</h4>
                                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500 max-w-xs">
                                    Klik tombol "Tambah Milestone" di atas untuk mulai membagikan perjalanan cinta Anda.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button 
                                type="submit"
                                class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                            >
                                Simpan Perubahan Cerita
                            </button>
                        </div>
                    </x-admin.card>
                </form>
            </div>

            <!-- TAB SUSUNAN ACARA -->
            <div x-show="activeTab === 'events'" x-cloak>
                <form action="{{ route('admin.invitations.content.event', $invitation->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="delete_event_ids" :value="deletedEventIds.join(',')">
                    
                    <x-admin.card>
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-slate-800 dark:text-white">
                                    Susunan Acara Tambahan / Khusus
                                </h3>
                                <button 
                                    type="button" 
                                    @click="addEvent()"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-750 hover:bg-indigo-100 transition dark:bg-indigo-950/30 dark:text-indigo-400 dark:hover:bg-indigo-900/40"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Acara
                                </button>
                            </div>
                        </x-slot:header>

                        <!-- Alpine Dynamic Events List -->
                        <div class="space-y-6">
                            <template x-for="(event, index) in events" :key="index">
                                <div class="relative rounded-xl border border-slate-150 bg-slate-50/30 p-5 dark:border-slate-800 dark:bg-slate-900/30 space-y-4">
                                    <!-- Delete Button -->
                                    <button 
                                        type="button" 
                                        @click="removeEvent(index)"
                                        class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition"
                                        title="Hapus Acara"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                    <!-- Event ID -->
                                    <input type="hidden" :name="`events[${index}][id]`" x-model="event.id">

                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 pr-6">
                                        <!-- Name -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-650 dark:text-slate-350 mb-1">Nama Acara <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="text" 
                                                :name="`events[${index}][name]`" 
                                                x-model="event.name"
                                                required
                                                placeholder="Contoh: Unduh Mantu / Ramah Tamah"
                                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                                            />
                                        </div>

                                        <!-- Date -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-650 dark:text-slate-350 mb-1">Tanggal Acara <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="date" 
                                                :name="`events[${index}][date]`" 
                                                x-model="event.date"
                                                required
                                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                                            />
                                        </div>

                                        <!-- Time -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-650 dark:text-slate-350 mb-1">Waktu / Jam <span class="text-rose-500">*</span></label>
                                            <input 
                                                type="text" 
                                                :name="`events[${index}][time]`" 
                                                x-model="event.time"
                                                required
                                                placeholder="Contoh: 10:00 - Selesai"
                                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                                            />
                                        </div>
                                    </div>

                                    <!-- Location -->
                                    <div class="pr-6">
                                        <label class="block text-xs font-bold text-slate-650 dark:text-slate-350 mb-1">Tempat / Lokasi Acara <span class="text-rose-500">*</span></label>
                                        <textarea 
                                            :name="`events[${index}][location]`" 
                                            x-model="event.location"
                                            rows="2"
                                            required
                                            placeholder="Tulis alamat lengkap atau lokasi khusus acara..."
                                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                                        ></textarea>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty State inside tab -->
                            <div x-show="events.length === 0" class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="rounded-full bg-slate-100 p-3 dark:bg-slate-800">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h4 class="mt-3 font-semibold text-slate-800 dark:text-white text-sm">Belum Ada Susunan Acara</h4>
                                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500 max-w-xs">
                                    Klik tombol "Tambah Acara" di atas untuk menambahkan detail acara pendukung undangan Anda.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button 
                                type="submit"
                                class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                            >
                                Simpan Perubahan Acara
                            </button>
                        </div>
                    </x-admin.card>
                </form>
            </div>

            <!-- TAB MUSIK LATAR -->
            <div x-show="activeTab === 'music'" x-cloak class="space-y-6">
                <!-- Current Music Player Card -->
                <x-admin.card title="Musik Latar Terpasang">
                    @if($invitation->music->first())
                        @php
                            $currentMusic = $invitation->music->first();
                        @endphp
                        <div class="rounded-xl border border-slate-150 bg-slate-50/50 p-5 dark:border-slate-800 dark:bg-slate-900/30 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 dark:border-slate-800 dark:bg-slate-950 flex-shrink-0">
                                    @if($currentMusic->cover)
                                        <img src="{{ asset($currentMusic->cover) }}" alt="Cover" class="h-full w-full object-cover" />
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-indigo-50 text-indigo-550 dark:bg-indigo-950/40 dark:text-indigo-400">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-white text-sm">{{ $currentMusic->title }}</h4>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $currentMusic->artist }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 text-xxs font-semibold text-indigo-750 dark:bg-indigo-950/40 dark:text-indigo-400">
                                            Mood: {{ $currentMusic->mood }}
                                        </span>
                                        @if($invitation->wedding_mood)
                                            <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-0.5 text-xxs font-semibold text-rose-750 dark:bg-rose-950/40 dark:text-rose-400">
                                                Wedding Mood: {{ $invitation->wedding_mood }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow max-w-md">
                                <audio controls class="w-full h-8 focus:outline-none">
                                    <source src="{{ asset($currentMusic->file) }}" type="audio/mpeg">
                                    <source src="{{ asset($currentMusic->file) }}" type="audio/wav">
                                    Browser Anda tidak mendukung pemutar audio ini.
                                </audio>
                            </div>
                            <div>
                                <form 
                                    action="{{ route('admin.invitations.content.music', $invitation->id) }}" 
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus musik latar ini?');"
                                >
                                    @csrf
                                    <input type="hidden" name="action" value="delete">
                                    <button 
                                        type="submit" 
                                        class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100 transition dark:border-rose-950 dark:bg-rose-950/20 dark:text-rose-400 dark:hover:bg-rose-900/30 whitespace-nowrap w-full md:w-auto"
                                    >
                                        Hapus Musik
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="rounded-full bg-slate-100 p-3 dark:bg-slate-800">
                                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>
                            <h4 class="mt-4 font-semibold text-slate-800 dark:text-white">Musik Latar Belum Dipasang</h4>
                            <p class="mt-1 text-sm text-slate-400 dark:text-slate-500 max-w-xs">
                                Pilih lagu dari perpustakaan di bawah untuk memasang musik latar otomatis pada undangan digital Anda.
                            </p>
                        </div>
                    @endif
                </x-admin.card>

                <!-- Music Selection Panel -->
                <x-admin.card title="Perpustakaan & Rekomendasi Musik">
                    <!-- Hidden Select Form -->
                    <form action="{{ route('admin.invitations.content.music', $invitation->id) }}" method="POST" id="music-select-form" class="hidden">
                        @csrf
                        <input type="hidden" name="action" value="select">
                        <input type="hidden" name="music_id" :value="selectedMusicId">
                        <input type="hidden" name="wedding_mood" :value="weddingMood">
                    </form>

                    <!-- Mood Selection Row -->
                    <div class="mb-6 p-4 rounded-xl bg-indigo-50/50 dark:bg-slate-900/40 border border-indigo-100/50 dark:border-slate-800/80 max-w-md">
                        <label class="block text-xs font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider mb-2">Pilih Wedding Mood Pernikahan Anda</label>
                        <select 
                            x-model="weddingMood" 
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                        >
                            <option value="">Pilih Mood Pernikahan</option>
                            <option value="Romantic">Romantic</option>
                            <option value="Elegant">Elegant</option>
                            <option value="Luxury">Luxury</option>
                            <option value="Islamic">Islamic</option>
                            <option value="Classic">Classic</option>
                            <option value="Modern">Modern</option>
                            <option value="Acoustic">Acoustic</option>
                            <option value="Instrumental">Instrumental</option>
                        </select>
                        <p class="mt-1.5 text-xxs text-slate-400">Rekomendasi lagu akan langsung disesuaikan berdasarkan mood pernikahan terpilih.</p>
                    </div>

                    <!-- Recommended Tracks Carousel/List -->
                    <div class="mb-8 space-y-4">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-1.5">
                            <span class="text-rose-500">★</span> Recommended For Your Wedding
                        </h4>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                            <template x-for="music in recommendedMusic" :key="music.id">
                                <div 
                                    class="group relative overflow-hidden rounded-xl border p-4 bg-white dark:bg-slate-900 transition flex flex-col justify-between"
                                    :class="selectedMusicId === music.id ? 'border-rose-450 ring-1 ring-rose-250 bg-rose-50/10 dark:bg-rose-950/5' : 'border-slate-150 hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700'"
                                >
                                    <div class="flex gap-3">
                                        <!-- Cover Art -->
                                        <div class="h-14 w-14 rounded-lg overflow-hidden border border-slate-100 bg-slate-50 dark:border-slate-800 dark:bg-slate-950 flex-shrink-0">
                                            <template x-if="music.cover">
                                                <img :src="music.cover" alt="Cover" class="h-full w-full object-cover" />
                                            </template>
                                            <template x-if="!music.cover">
                                                <div class="h-full w-full flex items-center justify-center bg-indigo-50/70 text-indigo-550 dark:bg-indigo-950/20 dark:text-indigo-400">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Title & Artist -->
                                        <div class="min-w-0 flex-grow">
                                            <h5 class="font-bold text-slate-800 dark:text-white text-xs truncate" x-text="music.title"></h5>
                                            <p class="text-xxs text-slate-400 dark:text-slate-500 mt-0.5 truncate" x-text="music.artist"></p>
                                            <span class="inline-block mt-1.5 rounded-full bg-rose-50 px-2 py-0.5 text-xxs font-semibold text-rose-700 dark:bg-rose-950/30 dark:text-rose-400" x-text="music.mood"></span>
                                        </div>
                                    </div>

                                    <!-- Bottom Control row -->
                                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                        <!-- Play Preview Button -->
                                        <button 
                                            type="button" 
                                            @click="togglePreview(music)"
                                            class="inline-flex items-center gap-1 text-xxs font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400"
                                        >
                                            <svg x-show="previewPlayingId !== music.id" class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                            <svg x-show="previewPlayingId === music.id" class="h-4 w-4 fill-current" viewBox="0 0 24 24" x-cloak>
                                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                            </svg>
                                            <span x-text="previewPlayingId === music.id ? 'Memutar...' : 'Preview'"></span>
                                        </button>

                                        <!-- Select Button -->
                                        <button 
                                            type="button"
                                            @click="selectMusic(music.id)"
                                            class="rounded-lg px-2.5 py-1 text-xxs font-bold transition duration-150"
                                            :class="selectedMusicId === music.id ? 'bg-rose-500 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-350 dark:hover:bg-slate-700'"
                                        >
                                            <span x-text="selectedMusicId === music.id ? '✓ Terpasang' : 'Pilih Lagu'"></span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <div x-show="recommendedMusic.length === 0" class="p-6 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 text-slate-400 text-xs">
                            Tidak ada rekomendasi lagu untuk tema dan mood terpilih saat ini. Silakan cari lagu di perpustakaan di bawah.
                        </div>
                    </div>

                    <!-- Library search and filter section -->
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-6 space-y-4">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">
                            Semua Koleksi Musik Latar
                        </h4>

                        <!-- Search and filter inputs -->
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4">
                            <!-- Search -->
                            <div>
                                <input 
                                    type="text" 
                                    x-model="musicSearch"
                                    placeholder="Cari judul, artis..."
                                    class="w-full rounded-xl border border-slate-200 bg-transparent px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white"
                                />
                            </div>
                            
                            <!-- Mood Filter -->
                            <div>
                                <select 
                                    x-model="musicFilterMood"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                                >
                                    <option value="">Semua Mood</option>
                                    <option value="Romantic">Romantic</option>
                                    <option value="Elegant">Elegant</option>
                                    <option value="Luxury">Luxury</option>
                                    <option value="Islamic">Islamic</option>
                                    <option value="Classic">Classic</option>
                                    <option value="Modern">Modern</option>
                                    <option value="Acoustic">Acoustic</option>
                                    <option value="Instrumental">Instrumental</option>
                                </select>
                            </div>

                            <!-- Genre Filter -->
                            <div>
                                <select 
                                    x-model="musicFilterGenre"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                                >
                                    <option value="">Semua Genre</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Pop">Pop</option>
                                    <option value="Instrumental">Instrumental</option>
                                </select>
                            </div>

                            <!-- Language Filter -->
                            <div>
                                <select 
                                    x-model="musicFilterLanguage"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                                >
                                    <option value="">Semua Bahasa</option>
                                    <option value="Inggris">Inggris</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Instrumental">Instrumental</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtered Tracks Grid -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 pt-2">
                            <template x-for="music in filteredMusic" :key="music.id">
                                <div 
                                    class="group relative overflow-hidden rounded-xl border p-4 bg-white dark:bg-slate-900 transition flex flex-col justify-between"
                                    :class="selectedMusicId === music.id ? 'border-rose-450 ring-1 ring-rose-250 bg-rose-50/10 dark:bg-rose-950/5' : 'border-slate-150 hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700'"
                                >
                                    <div class="flex gap-3">
                                        <!-- Cover Art -->
                                        <div class="h-14 w-14 rounded-lg overflow-hidden border border-slate-100 bg-slate-50 dark:border-slate-800 dark:bg-slate-950 flex-shrink-0">
                                            <template x-if="music.cover">
                                                <img :src="music.cover" alt="Cover" class="h-full w-full object-cover" />
                                            </template>
                                            <template x-if="!music.cover">
                                                <div class="h-full w-full flex items-center justify-center bg-indigo-50/70 text-indigo-550 dark:bg-indigo-950/20 dark:text-indigo-400">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Title & Artist -->
                                        <div class="min-w-0 flex-grow">
                                            <h5 class="font-bold text-slate-800 dark:text-white text-xs truncate" x-text="music.title"></h5>
                                            <p class="text-xxs text-slate-400 dark:text-slate-500 mt-0.5 truncate" x-text="music.artist"></p>
                                            <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                                                <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xxs font-semibold text-indigo-750 dark:bg-indigo-950/40 dark:text-indigo-400" x-text="music.mood"></span>
                                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xxs font-semibold text-slate-650 dark:bg-slate-800 dark:text-slate-400" x-text="music.genre"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bottom Control row -->
                                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                        <!-- Play Preview Button -->
                                        <button 
                                            type="button" 
                                            @click="togglePreview(music)"
                                            class="inline-flex items-center gap-1 text-xxs font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400"
                                        >
                                            <svg x-show="previewPlayingId !== music.id" class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                            <svg x-show="previewPlayingId === music.id" class="h-4 w-4 fill-current" viewBox="0 0 24 24" x-cloak>
                                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                            </svg>
                                            <span x-text="previewPlayingId === music.id ? 'Memutar...' : 'Preview'"></span>
                                        </button>

                                        <!-- Select Button -->
                                        <button 
                                            type="button"
                                            @click="selectMusic(music.id)"
                                            class="rounded-lg px-2.5 py-1 text-xxs font-bold transition duration-150"
                                            :class="selectedMusicId === music.id ? 'bg-rose-500 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-350 dark:hover:bg-slate-700'"
                                        >
                                            <span x-text="selectedMusicId === music.id ? '✓ Terpasang' : 'Pilih Lagu'"></span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="filteredMusic.length === 0" class="p-8 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 text-slate-400 text-xs" x-cloak>
                            Tidak ada lagu di perpustakaan yang cocok dengan pencarian dan filter Anda.
                        </div>
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>
@endsection
