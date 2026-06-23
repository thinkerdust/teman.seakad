@extends('admin.layouts.app')

@section('title', 'Ubah Musik Latar')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Ubah Musik Latar" :items="['Kelola Musik Latar' => route('admin.music.index'), 'Ubah Musik Latar' => '']" />

    <!-- Alert Messages / Error Summary -->
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

    <div class="max-w-3xl">
        <x-admin.card title="Form Ubah Musik Latar">
            <form 
                action="{{ route('admin.music.update', $music->id) }}" 
                method="POST" 
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Judul Lagu <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="title" 
                            value="{{ old('title', $music->title) }}"
                            required
                            placeholder="Contoh: A Thousand Years"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                    </div>

                    <!-- Artist -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Artis <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="artist" 
                            value="{{ old('artist', $music->artist) }}"
                            required
                            placeholder="Contoh: Christina Perri"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                    </div>

                    <!-- Album -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Album (Opsional)</label>
                        <input 
                            type="text" 
                            name="album" 
                            value="{{ old('album', $music->album) }}"
                            placeholder="Contoh: Lovestrong"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                    </div>

                    <!-- Genre -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Genre <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="genre" 
                            value="{{ old('genre', $music->genre) }}"
                            required
                            placeholder="Contoh: Wedding, Pop, Instrumental"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                    </div>

                    <!-- Mood -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Mood Lagu <span class="text-rose-500">*</span></label>
                        <select 
                            name="mood" 
                            required
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Pilih Mood</option>
                            @foreach($moods as $mood)
                                <option value="{{ $mood }}" {{ old('mood', $music->mood) == $mood ? 'selected' : '' }}>{{ $mood }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Bahasa (Opsional)</label>
                        <input 
                            type="text" 
                            name="language" 
                            value="{{ old('language', $music->language) }}"
                            placeholder="Contoh: Inggris, Indonesia, Instrumental"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Durasi (Opsional)</label>
                        <input 
                            type="text" 
                            name="duration" 
                            value="{{ old('duration', $music->duration) }}"
                            placeholder="Contoh: 04:12"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select 
                            name="status" 
                            required
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="active" {{ old('status', $music->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $music->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Preview URL -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tautan Pratinjau Eksternal (Opsional)</label>
                    <input 
                        type="url" 
                        name="preview_url" 
                        value="{{ old('preview_url', $music->preview_url) }}"
                        placeholder="Contoh: https://spotify.com/... atau https://youtube.com/..."
                        class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                    />
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Cover Image Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gambar Sampul (Cover Art)</label>
                        
                        @if($music->cover)
                            <div class="mb-3 flex items-center gap-3 bg-slate-50 dark:bg-slate-850 p-2 rounded-xl border border-slate-100 dark:border-slate-800">
                                <img src="{{ asset($music->cover) }}" class="h-14 w-14 object-cover rounded-lg" alt="Cover Current" />
                                <span class="text-xs text-slate-400">Sampul saat ini</span>
                            </div>
                        @endif

                        <input 
                            type="file" 
                            name="cover" 
                            accept="image/*"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-800"
                        />
                        <p class="mt-1 text-xxs text-slate-400">Pilih berkas baru jika ingin mengganti sampul.</p>
                    </div>

                    <!-- Music Audio Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Berkas Audio (MP3 / WAV)</label>
                        
                        @if($music->file)
                            <div class="mb-3 p-2 bg-slate-50 dark:bg-slate-850 rounded-xl border border-slate-100 dark:border-slate-800 space-y-2">
                                <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold">Lagu saat ini:</span>
                                <audio controls class="w-full h-8 focus:outline-none">
                                    <source src="{{ asset($music->file) }}" type="audio/mpeg">
                                    <source src="{{ asset($music->file) }}" type="audio/wav">
                                    Browser tidak mendukung.
                                </audio>
                            </div>
                        @endif

                        <input 
                            type="file" 
                            name="music_file" 
                            accept="audio/mp3,audio/mpeg,audio/wav,audio/x-wav"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-800"
                        />
                        <p class="mt-1 text-xxs text-slate-400">Pilih berkas baru jika ingin mengganti file audio.</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                    <a 
                        href="{{ route('admin.music.index') }}" 
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
