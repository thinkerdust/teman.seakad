@extends('admin.layouts.app')

@section('title', 'Tambah Musik Latar Baru')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Tambah Musik Latar" :items="['Kelola Musik Latar' => route('admin.music.index'), 'Tambah Musik Latar' => '']" />

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
        <x-admin.card title="Form Tambah Musik Latar Baru">
            <form 
                action="{{ route('admin.music.store') }}" 
                method="POST" 
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Judul Lagu <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="title" 
                            value="{{ old('title') }}"
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
                            value="{{ old('artist') }}"
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
                            value="{{ old('album') }}"
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
                            value="{{ old('genre', 'Wedding') }}"
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
                                <option value="{{ $mood }}" {{ old('mood') == $mood ? 'selected' : '' }}>{{ $mood }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Bahasa (Opsional)</label>
                        <input 
                            type="text" 
                            name="language" 
                            value="{{ old('language') }}"
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
                            value="{{ old('duration') }}"
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
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Preview URL -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tautan Pratinjau Eksternal (Opsional)</label>
                    <input 
                        type="url" 
                        name="preview_url" 
                        value="{{ old('preview_url') }}"
                        placeholder="Contoh: https://spotify.com/... atau https://youtube.com/..."
                        class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                    />
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Cover Image Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gambar Sampul (Cover Art)</label>
                        <input 
                            type="file" 
                            name="cover" 
                            accept="image/*"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-800"
                        />
                        <p class="mt-1 text-xxs text-slate-400">Rekomendasi ukuran square 1:1, PNG, JPG hingga 2MB.</p>
                    </div>

                    <!-- Music Audio Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Berkas Audio (MP3 / WAV) <span class="text-rose-500">*</span></label>
                        <input 
                            type="file" 
                            name="music_file" 
                            accept="audio/mp3,audio/mpeg,audio/wav,audio/x-wav"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-800"
                        />
                        <p class="mt-1 text-xxs text-slate-400">Wajib diunggah. Format MP3 atau WAV hingga 20MB.</p>
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
                        Simpan Lagu
                    </button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
