@extends('admin.layouts.app')

@section('title', 'Package Management')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Package Management" :items="['Package Management' => '']" />

    <!-- Main Container -->
    <div 
        x-data="packagesManager({
            hasErrors: @json($errors->any()),
            oldId: '{{ old('id', '') }}',
            oldName: '{{ old('name', '') }}',
            oldDescription: '{{ old('description', '') }}',
            oldPrice: '{{ old('price', 0) }}',
            oldInvitationQuota: '{{ old('invitation_quota', 1) }}',
            oldDurationDays: '{{ old('duration_days', 30) }}',
            oldStatus: '{{ old('status', 'active') }}'
        })"
    >
        <!-- Table & Filter Card -->
        <div id="packages-table-container">
            <x-admin.card>
                <x-slot:header>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Daftar Paket Undangan
                    </h3>
                    
                    @if(auth()->user()->hasPermission('package.create'))
                    <button 
                        @click="createModalOpen = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                    >
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Paket
                    </button>
                    @endif
                </div>
            </x-slot:header>

            <!-- Filters -->
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <!-- Search Form -->
                <form action="{{ route('admin.packages.index') }}" method="GET" class="flex-grow max-w-md">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari nama paket..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.packages.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-rose-500 hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Status Filter Dropdown -->
                <form id="filter-form" action="{{ route('admin.packages.index') }}" method="GET" class="flex items-center gap-3">
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

            <!-- Datatable -->
            <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-850">
                <table class="w-full border-collapse text-left text-sm text-slate-600 dark:text-slate-400">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider dark:bg-slate-900/50 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Nama Paket</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4 text-center">Kuota Undangan</th>
                            <th class="px-6 py-4 text-center">Durasi (Hari)</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($packages as $pkg)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                                    <button 
                                        @click="showDetail({{ json_encode($pkg) }})"
                                        class="hover:text-indigo-600 hover:underline text-left focus:outline-none"
                                    >
                                        {{ $pkg->name }}
                                    </button>
                                    @if($pkg->description)
                                        <div class="text-xs text-slate-405 font-normal max-w-xs truncate">{{ $pkg->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-850 dark:text-white">
                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-slate-800 dark:text-white">
                                    {{ $pkg->invitation_quota }}
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-slate-800 dark:text-white">
                                    {{ $pkg->duration_days }} Hari
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pkg->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span>
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Details -->
                                        @if(auth()->user()->hasPermission('package.update'))
                                        <button 
                                            @click="editPackage({{ json_encode($pkg) }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                            title="Edit Paket"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        @endif

                                        <!-- Delete -->
                                        @if(auth()->user()->hasPermission('package.delete'))
                                        <button 
                                            @click="confirmDelete({{ json_encode($pkg) }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-white text-rose-600 hover:bg-rose-50 dark:border-rose-950/20 dark:bg-slate-900 dark:text-rose-400 dark:hover:bg-rose-950/40"
                                            title="Hapus Paket"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500">
                                    Tidak ada data paket undangan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $packages->links() }}
            </div>
        </x-admin.card>
    </div>

    <!-- Modals -->
    <!-- 1. Detail Paket Modal -->
    <div 
        x-show="detailModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="detailModalOpen = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white" x-text="selectedPackage.name"></h3>
                <button @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4 text-sm mb-6">
                <div>
                    <span class="text-slate-400 font-medium">Deskripsi:</span>
                    <p class="text-slate-800 dark:text-slate-200 font-medium mt-1" x-text="selectedPackage.description || '-'"></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 font-medium">Harga:</span>
                        <div class="text-slate-800 dark:text-white font-semibold mt-0.5" x-text="'Rp ' + Number(selectedPackage.price).toLocaleString('id-ID')"></div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">Kuota Undangan:</span>
                        <div class="text-slate-800 dark:text-white font-semibold mt-0.5" x-text="selectedPackage.invitation_quota + ' Undangan'"></div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">Durasi Aktif:</span>
                        <div class="text-slate-800 dark:text-white font-semibold mt-0.5" x-text="selectedPackage.duration_days + ' Hari'"></div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">Status:</span>
                        <div class="mt-0.5">
                            <span 
                                x-text="selectedPackage.status === 'active' ? 'AKTIF' : 'NON-AKTIF'"
                                class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold"
                                :class="selectedPackage.status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'"
                            ></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                <button 
                    @click="detailModalOpen = false" 
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Tambah Paket Modal -->
    <div 
        x-show="createModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="closeCreateModal()"
            class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Tambah Paket Baru</h3>
                <button @click="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="create-package-form" action="{{ route('admin.packages.store') }}" method="POST" @submit.prevent="submitForm($event, 'create')">
                @csrf
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="create_name" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Nama Paket <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            id="create_name" 
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <template x-if="errors.name">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.name[0]"></span>
                        </template>
                    </div>

                    <!-- Price, Quota, Duration -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="create_price" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Harga (Rp) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="create_price" 
                                name="price"
                                value="{{ old('price', 0) }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.price">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.price[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="create_invitation_quota" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Kuota Undangan <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="create_invitation_quota" 
                                name="invitation_quota"
                                value="{{ old('invitation_quota', 1) }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.invitation_quota">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.invitation_quota[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="create_duration_days" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Durasi (Hari) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="create_duration_days" 
                                name="duration_days"
                                value="{{ old('duration_days', 30) }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.duration_days">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.duration_days[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="create_status" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Status <span class="text-rose-500">*</span></label>
                        <select 
                            id="create_status" 
                            name="status"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        >
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        <template x-if="errors.status">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.status[0]"></span>
                        </template>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="create_description" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Deskripsi Paket</label>
                        <textarea 
                            id="create_description" 
                            name="description"
                            rows="3"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        >{{ old('description') }}</textarea>
                        <template x-if="errors.description">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.description[0]"></span>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button 
                        type="button"
                        @click="closeCreateModal()" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
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

    <!-- 3. Edit Paket Modal -->
    <div 
        x-show="editModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="closeEditModal()"
            class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Edit Paket: <span x-text="selectedPackage.name" class="text-indigo-600 dark:text-indigo-400"></span></h3>
                <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-package-form" :action="'/admin/packages/' + selectedPackage.id" method="POST" @submit.prevent="submitForm($event, 'edit')">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="edit_name" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Nama Paket <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            id="edit_name" 
                            name="name"
                            x-model="selectedPackage.name"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <template x-if="errors.name">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.name[0]"></span>
                        </template>
                    </div>

                    <!-- Price, Quota, Duration -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="edit_price" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Harga (Rp) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="edit_price" 
                                name="price"
                                x-model="selectedPackage.price"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.price">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.price[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="edit_invitation_quota" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Kuota Undangan <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="edit_invitation_quota" 
                                name="invitation_quota"
                                x-model="selectedPackage.invitation_quota"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.invitation_quota">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.invitation_quota[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="edit_duration_days" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Durasi (Hari) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="edit_duration_days" 
                                name="duration_days"
                                x-model="selectedPackage.duration_days"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.duration_days">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.duration_days[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="edit_status" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Status <span class="text-rose-500">*</span></label>
                        <select 
                            id="edit_status" 
                            name="status"
                            x-model="selectedPackage.status"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        >
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                        <template x-if="errors.status">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.status[0]"></span>
                        </template>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="edit_description" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Deskripsi Paket</label>
                        <textarea 
                            id="edit_description" 
                            name="description"
                            x-model="selectedPackage.description"
                            rows="3"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <template x-if="errors.description">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.description[0]"></span>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button 
                        type="button"
                        @click="closeEditModal()" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
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

    <!-- 4. Delete Paket Confirmation Modal -->
    <div 
        x-show="deleteModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="deleteModalOpen = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center gap-3 text-rose-600 dark:text-rose-500 mb-4">
                <div class="h-10 w-10 rounded-full bg-rose-50 dark:bg-rose-950/50 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Konfirmasi Hapus Paket</h3>
            </div>

            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                Apakah Anda yakin ingin menghapus paket <strong x-text="selectedPackage.name"></strong>? Data pesanan yang sudah mengaitkan paket ini akan memiliki referensi paket yang kosong (`null`), tetapi riwayat data pesanan tidak akan terhapus.
            </p>

            <form :action="'/admin/packages/' + selectedPackage.id" method="POST" @submit.prevent="submitForm($event, 'delete')">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button 
                        type="button"
                        @click="deleteModalOpen = false" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Hapus Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
