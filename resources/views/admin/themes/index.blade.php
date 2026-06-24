@extends('admin.layouts.app')

@section('title', 'Theme Management')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Theme Management" :items="['Theme Management' => '']" />

    <!-- Main Container -->
    <div 
        x-data="themesManager({
            hasErrors: @json($errors->any()),
            oldId: '{{ old('id', '') }}',
            oldName: '{{ old('name', '') }}',
            oldSlug: '{{ old('slug', '') }}',
            oldFolder: '{{ old('folder', '') }}',
            oldDescription: '{{ old('description', '') }}',
            oldStatus: '{{ old('status', 'active') }}'
        })"
    >
        <!-- Card Wrapper -->
        <div id="themes-table-container">
            <x-admin.card>
                <x-slot:header>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                            Daftar Tema Undangan
                        </h3>
                        
                        @if(auth()->user()->hasPermission('theme.create'))
                        <button 
                            @click="createModalOpen = true"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                        >
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Tema
                        </button>
                        @endif
                    </div>
                </x-slot:header>

                <!-- Filters -->
                <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <!-- Search Form -->
                    <form action="{{ route('admin.themes.index') }}" method="GET" class="flex-grow max-w-md">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Cari nama tema atau deskripsi..."
                                class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.themes.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-rose-500 hover:underline">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Status Filter Dropdown -->
                    <form id="filter-form" action="{{ route('admin.themes.index') }}" method="GET" class="flex items-center gap-3">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <label for="status-filter" class="text-sm font-medium text-slate-500 dark:text-slate-400">Status:</label>
                        <select 
                            id="status-filter" 
                            name="status" 
                            onchange="document.getElementById('filter-form').submit()"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Semua</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </form>
                </div>

                <!-- Beautiful Themes Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($themes as $theme)
                        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/50">
                            <!-- Thumbnail Cover -->
                            <div class="relative aspect-video w-full overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @if($theme->thumbnail)
                                    <img 
                                        src="{{ asset($theme->thumbnail) }}" 
                                        alt="{{ $theme->name }}" 
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    />
                                @else
                                    <div class="flex h-full w-full flex-col items-center justify-center text-slate-300 dark:text-slate-600">
                                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="mt-2 text-xs font-semibold uppercase tracking-wider">No Preview</span>
                                    </div>
                                @endif

                                <!-- Status Badge overlay -->
                                <div class="absolute right-3 top-3">
                                    @if($theme->status === 'active')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 px-2.5 py-1 text-xs font-bold text-white shadow-sm backdrop-blur-sm">
                                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-500/90 px-2.5 py-1 text-xs font-bold text-white shadow-sm backdrop-blur-sm">
                                            <span class="h-1.5 w-1.5 rounded-full bg-white/60"></span>
                                            Non-Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-bold text-slate-800 dark:text-white transition group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                            {{ $theme->name }}
                                        </h4>
                                        <code class="mt-1 block text-xxs font-mono text-slate-400 dark:text-slate-500">
                                            slug: {{ $theme->slug }}
                                        </code>
                                    </div>
                                </div>

                                <p class="mt-3 line-clamp-2 text-xs text-slate-500 dark:text-slate-400 leading-relaxed min-h-[32px]">
                                    {{ $theme->description ?: 'Tidak ada deskripsi untuk tema ini.' }}
                                </p>

                                <div class="mt-4 border-t border-slate-100 pt-3 flex items-center justify-between dark:border-slate-800">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                                        <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                        <span>Folder: <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $theme->folder }}</strong></span>
                                    </div>

                                    <!-- Actions button group -->
                                    <div class="flex items-center gap-1">
                                        <a 
                                            href="{{ route('themes.preview', $theme->slug) }}"
                                            target="_blank"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                                            title="Pratinjau Tema"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if(auth()->user()->hasPermission('theme.update'))
                                        <button 
                                            @click="editTheme({{ json_encode($theme) }})"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                                            title="Edit Tema"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        @endif

                                        @if(auth()->user()->hasPermission('theme.delete'))
                                        <button 
                                            @click="confirmDelete({{ json_encode($theme) }})"
                                            class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                                            title="Hapus Tema"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400 dark:text-slate-500 bg-slate-50/50 dark:bg-slate-900/10 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                            <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="mt-4 font-semibold text-slate-850 dark:text-white">Tidak Ada Tema</h4>
                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Tidak ada tema yang cocok dengan pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Links -->
                <div class="mt-6">
                    {{ $themes->links() }}
                </div>
            </x-admin.card>
        </div>

        <!-- CREATE THEME MODAL -->
        @if(auth()->user()->hasPermission('theme.create'))
        <div 
            x-show="createModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="closeCreateModal()"></div>
            
            <!-- Modal Body -->
            <div 
                class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="createModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Tambah Tema Baru
                    </h3>
                    <button @click="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form 
                    id="create-theme-form"
                    action="{{ route('admin.themes.store') }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    @submit.prevent="submitForm($event, 'create')"
                    class="mt-4 space-y-4"
                >
                    @csrf
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Tema <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="selectedTheme.name"
                            placeholder="Contoh: Floral Elegant"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.name" x-text="errors.name ? errors.name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Slug Tema <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="slug" 
                            x-model="selectedTheme.slug"
                            placeholder="Contoh: floral-elegant"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.slug" x-text="errors.slug ? errors.slug[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Folder Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Folder Template <span class="text-rose-500">*</span></label>
                        <select 
                            name="folder" 
                            x-model="selectedTheme.folder"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Pilih Folder Template</option>
                            @foreach($availableFolders as $folder)
                                <option value="{{ $folder }}">{{ $folder }}</option>
                            @endforeach
                            @if(empty($availableFolders))
                                <option value="" disabled>(Tidak ada folder template di resources/js/invitation/templates)</option>
                            @endif
                        </select>
                        <span x-show="errors.folder" x-text="errors.folder ? errors.folder[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        <p class="mt-1 text-xxs text-slate-400">Terbaca dinamis dari direktori <code>resources/js/invitation/templates/</code></p>
                    </div>

                    <!-- Thumbnail upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gambar Sampul (Thumbnail)</label>
                        <input 
                            type="file" 
                            name="thumbnail" 
                            accept="image/*"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-800"
                        />
                        <span x-show="errors.thumbnail" x-text="errors.thumbnail ? errors.thumbnail[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select 
                            name="status" 
                            x-model="selectedTheme.status"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                        <span x-show="errors.status" x-text="errors.status ? errors.status[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi Tema</label>
                        <textarea 
                            name="description" 
                            x-model="selectedTheme.description"
                            rows="3"
                            placeholder="Deskripsi singkat mengenai tema..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <span x-show="errors.description" x-text="errors.description ? errors.description[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button 
                            type="button" 
                            @click="closeCreateModal()"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition disabled:opacity-50"
                        >
                            <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- EDIT THEME MODAL -->
        @if(auth()->user()->hasPermission('theme.update'))
        <div 
            x-show="editModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="closeEditModal()"></div>
            
            <!-- Modal Body -->
            <div 
                class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="editModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Ubah Tema Undangan
                    </h3>
                    <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form 
                    id="edit-theme-form"
                    :action="`{{ url('/admin/themes') }}/${selectedTheme.id}`" 
                    method="POST" 
                    enctype="multipart/form-data"
                    @submit.prevent="submitForm($event, 'edit')"
                    class="mt-4 space-y-4"
                >
                    @csrf
                    @method('PUT')
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Tema <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="selectedTheme.name"
                            placeholder="Contoh: Floral Elegant"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.name" x-text="errors.name ? errors.name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Slug Tema <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="slug" 
                            x-model="selectedTheme.slug"
                            placeholder="Contoh: floral-elegant"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.slug" x-text="errors.slug ? errors.slug[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Folder Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Folder Template <span class="text-rose-500">*</span></label>
                        <select 
                            name="folder" 
                            x-model="selectedTheme.folder"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Pilih Folder Template</option>
                            @foreach($availableFolders as $folder)
                                <option value="{{ $folder }}">{{ $folder }}</option>
                            @endforeach
                            @if(empty($availableFolders))
                                <option value="" disabled>(Tidak ada folder template di resources/js/invitation/templates)</option>
                            @endif
                        </select>
                        <span x-show="errors.folder" x-text="errors.folder ? errors.folder[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Current Thumbnail cover & Thumbnail upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gambar Sampul (Thumbnail)</label>
                        
                        <div x-show="selectedTheme.thumbnail_url" class="mb-3 flex items-center gap-3 bg-slate-50 dark:bg-slate-800 p-2 rounded-lg">
                            <img :src="selectedTheme.thumbnail_url" class="h-14 w-24 object-cover rounded-md border border-slate-200 dark:border-slate-700" alt="Thumbnail Current" />
                            <span class="text-xs text-slate-400">Sampul saat ini</span>
                        </div>

                        <input 
                            type="file" 
                            name="thumbnail" 
                            accept="image/*"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-800"
                        />
                        <span x-show="errors.thumbnail" x-text="errors.thumbnail ? errors.thumbnail[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select 
                            name="status" 
                            x-model="selectedTheme.status"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                        <span x-show="errors.status" x-text="errors.status ? errors.status[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi Tema</label>
                        <textarea 
                            name="description" 
                            x-model="selectedTheme.description"
                            rows="3"
                            placeholder="Deskripsi singkat mengenai tema..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <span x-show="errors.description" x-text="errors.description ? errors.description[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button 
                            type="button" 
                            @click="closeEditModal()"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition disabled:opacity-50"
                        >
                            <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- DELETE CONFIRMATION MODAL -->
        @if(auth()->user()->hasPermission('theme.delete'))
        <div 
            x-show="deleteModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="deleteModalOpen = false"></div>
            
            <!-- Modal Body -->
            <div 
                class="relative z-10 w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="deleteModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="text-center">
                    <!-- Icon Warning -->
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-500">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="mt-4 text-lg font-bold text-slate-850 dark:text-white">
                        Hapus Tema Undangan?
                    </h3>
                    
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus tema <strong class="text-slate-700 dark:text-slate-200 font-semibold" x-text="selectedTheme.name"></strong>? Tindakan ini bersifat permanen dan berkas gambar thumbnail juga akan dihapus dari server.
                    </p>
                </div>

                <!-- Form Hapus -->
                <form 
                    :action="`{{ url('/admin/themes') }}/${selectedTheme.id}`" 
                    method="POST"
                    @submit.prevent="submitForm($event, 'delete')"
                    class="mt-6 flex justify-center gap-3"
                >
                    @csrf
                    @method('DELETE')
                    
                    <button 
                        type="button" 
                        @click="deleteModalOpen = false"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    
                    <button 
                        type="submit" 
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 transition disabled:opacity-50"
                    >
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Hapus Tema
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
@endsection
