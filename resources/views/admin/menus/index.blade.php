@extends('admin.layouts.app')

@section('title', 'Menu Management')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Menu Management" :items="['Menu Management' => '']" />

    <!-- Main Container -->
    <div 
        x-data="menusManager({
            hasErrors: @json($errors->any()),
            oldId: '{{ old('id', '') }}',
            oldParentId: '{{ old('parent_id', '') }}',
            oldTitle: '{{ old('title', '') }}',
            oldIcon: '{{ old('icon', '') }}',
            oldRoute: '{{ old('route', '') }}',
            oldPermission: '{{ old('permission', '') }}',
            oldOrder: '{{ old('order', '0') }}',
            oldStatus: '{{ old('status', 'active') }}'
        })"
    >
        <!-- Table & Action Card -->
        <div id="menus-table-container">
            <x-admin.card>
                <x-slot:header>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Struktur Menu Navigasi
                    </h3>
                    
                    @if(auth()->user()->hasPermission('menu.create'))
                    <button 
                        @click="createMenuModalOpen = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150 cursor-pointer"
                    >
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Menu
                    </button>
                    @endif
                </div>
            </x-slot:header>

            <!-- Datatable -->
            <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-850">
                <table class="w-full border-collapse text-left text-sm text-slate-600 dark:text-slate-400">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider dark:bg-slate-900/50 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Menu / Judul</th>
                            <th class="px-6 py-4">Rute Rujukan</th>
                            <th class="px-6 py-4">Permission</th>
                            <th class="px-6 py-4 text-center">Urutan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($menus as $root)
                            <!-- Root Menu Row -->
                            <tr class="bg-slate-50/40 dark:bg-slate-900/10 font-semibold text-slate-800 dark:text-white">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        @if($root->icon)
                                            <span class="text-slate-400 [&>svg]:h-5 [&>svg]:w-5">{!! $root->icon !!}</span>
                                        @else
                                            <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        @endif
                                        <span>{{ $root->title }} <span class="text-xs font-normal text-slate-400 dark:text-slate-500 ml-1">(Grup Menu)</span></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">-</td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">-</td>
                                <td class="px-6 py-4 text-center font-mono text-slate-500">{{ $root->order }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($root->status === 'active')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(auth()->user()->hasPermission('menu.update'))
                                        <button 
                                            @click="editMenu({{ json_encode($root) }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 cursor-pointer"
                                            title="Edit Menu"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        @endif

                                        @if(auth()->user()->hasPermission('menu.delete'))
                                        <button 
                                            @click="confirmDelete({{ json_encode($root) }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-white text-rose-600 hover:bg-rose-50 dark:border-rose-950/20 dark:bg-slate-900 dark:text-rose-400 dark:hover:bg-rose-950/40 cursor-pointer"
                                            title="Hapus Menu"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Sub Menus -->
                            @foreach($root->children as $child)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                    <td class="px-6 py-4 pl-12 text-slate-700 dark:text-slate-300">
                                        <div class="flex items-center gap-2">
                                            <span class="text-slate-400 font-normal">└— </span>
                                            @if($child->icon)
                                                <span class="text-slate-400 [&>svg]:h-4 [&>svg]:w-4">{!! $child->icon !!}</span>
                                            @endif
                                            <span>{{ $child->title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $child->route ?: '-' }}</td>
                                    <td class="px-6 py-4 text-xs font-mono text-indigo-600 dark:text-indigo-400">{{ $child->permission ?: '-' }}</td>
                                    <td class="px-6 py-4 text-center font-mono text-slate-500">{{ $child->order }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($child->status === 'active')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(auth()->user()->hasPermission('menu.update'))
                                            <button 
                                                @click="editMenu({{ json_encode($child) }})"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 cursor-pointer"
                                                title="Edit Menu"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            @endif

                                            @if(auth()->user()->hasPermission('menu.delete'))
                                            <button 
                                                @click="confirmDelete({{ json_encode($child) }})"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-white text-rose-600 hover:bg-rose-50 dark:border-rose-950/20 dark:bg-slate-900 dark:text-rose-400 dark:hover:bg-rose-950/40 cursor-pointer"
                                                title="Hapus Menu"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500">
                                    Tidak ada data menu ditemukan. Silakan tambahkan menu baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>
        </div>

        <!-- CREATE MENU MODAL -->
        <div 
            x-show="createMenuModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="closeCreateModal()"></div>
            
            <div 
                class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="createMenuModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Tambah Menu Baru
                    </h3>
                    <button @click="closeCreateModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="create-menu-form" action="{{ route('admin.menus.store') }}" method="POST" @submit.prevent="submitForm($event, 'create')" class="mt-4 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Parent Menu -->
                        <div>
                            <label for="create_parent_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Parent Menu</label>
                            <select id="create_parent_id" name="parent_id"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="">Tidak Ada (Jadikan Grup Utama)</option>
                                @foreach($parentOptions as $parent)
                                    <option value="{{ $parent->id }}" {{ (old('id') ? '' : old('parent_id')) == $parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                                @endforeach
                            </select>
                            <span x-show="errors.parent_id" x-text="errors.parent_id ? errors.parent_id[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="create_title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Judul Menu *</label>
                            <input type="text" id="create_title" name="title" required value="{{ old('id') ? '' : old('title') }}"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.title" x-text="errors.title ? errors.title[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Route -->
                        <div>
                            <label for="create_route" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Rute Aplikasi (Route Name)</label>
                            <input type="text" id="create_route" name="route" placeholder="Contoh: admin.users.index" value="{{ old('id') ? '' : old('route') }}"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.route" x-text="errors.route ? errors.route[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Permission Required -->
                        <div>
                            <label for="create_permission" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Permission Akses</label>
                            <select id="create_permission" name="permission"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="">Terbuka untuk Semua (Tanpa Izin Khusus)</option>
                                @foreach($permissions as $perm)
                                    <option value="{{ $perm->key }}" {{ (old('id') ? '' : old('permission')) === $perm->key ? 'selected' : '' }}>{{ $perm->key }} ({{ $perm->name }})</option>
                                @endforeach
                            </select>
                            <span x-show="errors.permission" x-text="errors.permission ? errors.permission[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Order -->
                        <div>
                            <label for="create_order" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor Urut *</label>
                            <input type="number" id="create_order" name="order" required min="0" value="{{ old('id') ? '0' : old('order', '0') }}"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.order" x-text="errors.order ? errors.order[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="create_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status Aktif *</label>
                            <select id="create_status" name="status" required
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="active" {{ (old('id') ? '' : old('status')) === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ (old('id') ? '' : old('status')) === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            <span x-show="errors.status" x-text="errors.status ? errors.status[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <!-- Icon (Raw SVG) -->
                    <div>
                        <label for="create_icon" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kode SVG Ikon (Optional)</label>
                        <textarea id="create_icon" name="icon" rows="3" placeholder="Masukkan tag <svg>...</svg> di sini"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-xs font-mono text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">{{ old('id') ? '' : old('icon') }}</textarea>
                        <span x-show="errors.icon" x-text="errors.icon ? errors.icon[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Footer actions -->
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button type="button" @click="closeCreateModal()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 cursor-pointer">
                            Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- EDIT MENU MODAL -->
        <div 
            x-show="editMenuModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="closeEditModal()"></div>
            
            <div 
                class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="editMenuModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Edit Menu: <span x-text="selectedMenu.title" class="text-indigo-600 dark:text-indigo-400"></span>
                    </h3>
                    <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="edit-menu-form" :action="'/admin/menus/' + selectedMenu.id" method="POST" @submit.prevent="submitForm($event, 'edit')" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" :value="selectedMenu.id">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Parent Menu -->
                        <div>
                            <label for="edit_parent_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Parent Menu</label>
                            <select id="edit_parent_id" name="parent_id" :value="selectedMenu.parent_id" @change="selectedMenu.parent_id = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="">Tidak Ada (Jadikan Grup Utama)</option>
                                @foreach($parentOptions as $parent)
                                    <!-- Prevent self selection in blade output (UpdateRequest also validates this) -->
                                    <option x-show="selectedMenu.id != {{ $parent->id }}" value="{{ $parent->id }}">{{ $parent->title }}</option>
                                @endforeach
                            </select>
                            <span x-show="errors.parent_id" x-text="errors.parent_id ? errors.parent_id[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="edit_title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Judul Menu *</label>
                            <input type="text" id="edit_title" name="title" required :value="selectedMenu.title" @input="selectedMenu.title = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.title" x-text="errors.title ? errors.title[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Route -->
                        <div>
                            <label for="edit_route" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Rute Aplikasi (Route Name)</label>
                            <input type="text" id="edit_route" name="route" :value="selectedMenu.route" @input="selectedMenu.route = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.route" x-text="errors.route ? errors.route[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Permission Required -->
                        <div>
                            <label for="edit_permission" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Permission Akses</label>
                            <select id="edit_permission" name="permission" :value="selectedMenu.permission" @change="selectedMenu.permission = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="">Terbuka untuk Semua (Tanpa Izin Khusus)</option>
                                @foreach($permissions as $perm)
                                    <option value="{{ $perm->key }}">{{ $perm->key }} ({{ $perm->name }})</option>
                                @endforeach
                            </select>
                            <span x-show="errors.permission" x-text="errors.permission ? errors.permission[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Order -->
                        <div>
                            <label for="edit_order" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor Urut *</label>
                            <input type="number" id="edit_order" name="order" required min="0" :value="selectedMenu.order" @input="selectedMenu.order = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.order" x-text="errors.order ? errors.order[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status Aktif *</label>
                            <select id="edit_status" name="status" required :value="selectedMenu.status" @change="selectedMenu.status = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non-Aktif</option>
                            </select>
                            <span x-show="errors.status" x-text="errors.status ? errors.status[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <!-- Icon (Raw SVG) -->
                    <div>
                        <label for="edit_icon" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kode SVG Ikon (Optional)</label>
                        <textarea id="edit_icon" name="icon" rows="3" :value="selectedMenu.icon" @input="selectedMenu.icon = $event.target.value"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-xs font-mono text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white"></textarea>
                        <span x-show="errors.icon" x-text="errors.icon ? errors.icon[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Footer actions -->
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button type="button" @click="closeEditModal()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE MENU CONFIRMATION MODAL -->
        <div 
            x-show="deleteMenuModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="deleteMenuModalOpen = false"></div>
            
            <div 
                class="relative z-10 w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="deleteMenuModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Hapus Menu Navigasi
                    </h3>
                    <button @click="deleteMenuModalOpen = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <h3 class="mt-4 text-md font-bold text-slate-800 dark:text-white">Apakah Anda yakin?</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        Menu <strong x-text="selectedMenu.title" class="text-slate-850 dark:text-white"></strong> akan dihapus permanen. Menghapus menu utama juga akan otomatis menghapus seluruh sub-menu di bawahnya.
                    </p>
                </div>

                <form :action="'/admin/menus/' + selectedMenu.id" method="POST" @submit.prevent="submitForm($event, 'delete')" class="mt-6">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button type="button" @click="deleteMenuModalOpen = false" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500 cursor-pointer">
                            Hapus Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
