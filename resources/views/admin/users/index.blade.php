@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="User Management" :items="['User Management' => '']" />

    <!-- Main Container -->
    <div 
        x-data="usersManager({
            hasErrors: @json($errors->any()),
            oldId: '{{ old('id', '') }}',
            oldName: '{{ old('name', '') }}',
            oldEmail: '{{ old('email', '') }}',
            oldPhone: '{{ old('phone', '') }}',
            oldStatus: '{{ old('status', 'active') }}'
        })"
    >
        <!-- Table & Filter Card -->
        <div id="users-table-container">
            <x-admin.card>
                <x-slot:header>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Daftar Pengguna
                    </h3>
                    
                    <button 
                        @click="createUserModalOpen = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                    >
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah User
                    </button>
                </div>
            </x-slot:header>

            <!-- Filters -->
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <!-- Search Form -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex-grow max-w-md">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari nama, email, atau no. telepon..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.users.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-rose-500 hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Status Filter Dropdown -->
                <form id="filter-form" action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-3">
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
                            <th class="px-6 py-4">Pengguna</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Login Terakhir</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-full border border-slate-200 dark:border-slate-700 bg-indigo-600 text-white font-bold flex items-center justify-center text-sm">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                                            @else
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800 dark:text-white">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-xs text-slate-400">
                                                Terdaftar: {{ $user->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-800 dark:text-white">{{ $user->email }}</div>
                                    <div class="text-xs text-slate-400">{{ $user->phone ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->status === 'active')
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
                                <td class="px-6 py-4">
                                    <span class="text-slate-500 dark:text-slate-400">
                                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah login' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Button -->
                                        <button 
                                            @click="editUser({{ json_encode($user) }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                            title="Edit Profil"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <!-- Reset Password Button -->
                                        <button 
                                            @click="openResetPassword({{ json_encode($user) }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                            title="Reset Password"
                                        >
                                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m-5-4v1a3 3 0 00-3 3H6a3 3 0 00-3 3 2 2 0 002 2h14a2 2 0 002-2m-4-3a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Button (Only visible if not current user and not superadmin default) -->
                                        @if($user->id !== Auth::id() && $user->email !== 'admin@teman-seakad.com')
                                            <button 
                                                @click="confirmDelete({{ json_encode($user) }})"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-white text-rose-600 hover:bg-rose-50 dark:border-rose-950/20 dark:bg-slate-900 dark:text-rose-400 dark:hover:bg-rose-950/40"
                                                title="Hapus User"
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
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500">
                                    Tidak ada pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </x-admin.card>
        </div>

        <!-- CREATE USER MODAL -->
        <div 
            x-show="createUserModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="closeCreateModal()"></div>
            
            <!-- Modal Body -->
            <div 
                class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="createUserModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Tambah User Baru
                    </h3>
                    <button @click="closeCreateModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="create-user-form" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event, 'create')" class="mt-4 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="create_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap *</label>
                            <input type="text" id="create_name" name="name" required value="{{ old('id') ? '' : old('name') }}"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.name" x-text="errors.name ? errors.name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('name')
                                @if(!old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="create_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email *</label>
                            <input type="email" id="create_email" name="email" required value="{{ old('id') ? '' : old('email') }}"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.email" x-text="errors.email ? errors.email[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('email')
                                @if(!old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Phone -->
                        <div>
                            <label for="create_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">No. Telepon</label>
                            <input type="text" id="create_phone" name="phone" value="{{ old('id') ? '' : old('phone') }}"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('phone')
                                @if(!old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="create_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status *</label>
                            <select id="create_status" name="status" required
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="active" {{ (old('id') ? '' : old('status')) === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ (old('id') ? '' : old('status')) === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            <span x-show="errors.status" x-text="errors.status ? errors.status[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('status')
                                @if(!old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Password -->
                        <div>
                            <label for="create_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password *</label>
                            <input type="password" id="create_password" name="password" required placeholder="Minimal 8 karakter"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.password" x-text="errors.password ? errors.password[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('password')
                                @if(!old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="create_password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password *</label>
                            <input type="password" id="create_password_confirmation" name="password_confirmation" required placeholder="Ulangi password"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                        </div>
                    </div>

                    <!-- Avatar -->
                    <div x-data="{ avatarPreview: '' }" x-on:reset-avatar.window="avatarPreview = ''">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Foto Avatar</label>
                        <div class="mt-2 flex items-center gap-4">
                            <!-- Image Preview Window -->
                            <div class="h-16 w-16 overflow-hidden rounded-full border border-slate-200 dark:border-slate-700 bg-slate-100 flex items-center justify-center text-slate-400 dark:bg-slate-800">
                                <template x-if="avatarPreview">
                                    <img :src="avatarPreview" alt="Preview" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!avatarPreview">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.964 18.901a11.182 11.182 0 00-3.228-3.143M8.91 17.5a4.75 4.75 0 011.89-3.48m.01-.01a11.173 11.173 0 003.228-3.143m0 0a11.18 11.18 0 01-3.228 3.143m0 0a4.75 4.75 0 00-1.89 3.48M12 21a9 9 0 110-18 9 9 0 010 18z" />
                                    </svg>
                                </template>
                            </div>
                            <!-- Upload Input Button -->
                            <input 
                                type="file" 
                                name="avatar" 
                                accept="image/*"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { avatarPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    } else {
                                        avatarPreview = '';
                                    }
                                "
                                class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/40 dark:file:text-indigo-400"
                            >
                        </div>
                        <span x-show="errors.avatar" x-text="errors.avatar ? errors.avatar[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        @error('avatar')
                            @if(!old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                        @enderror
                    </div>

                    <!-- Footer actions -->
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button type="button" @click="closeCreateModal()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- EDIT USER MODAL -->
        <div 
            x-show="editUserModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="closeEditModal()"></div>
            
            <!-- Modal Body -->
            <div 
                class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="editUserModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Edit User: <span x-text="selectedUser.name" class="text-indigo-600 dark:text-indigo-400"></span>
                    </h3>
                    <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="edit-user-form" :action="'/admin/users/' + selectedUser.id" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event, 'edit')" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Hidden old ID input to verify this request is an edit -->
                    <input type="hidden" name="id" :value="selectedUser.id">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap *</label>
                            <input type="text" id="edit_name" name="name" required :value="selectedUser.name" @input="selectedUser.name = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.name" x-text="errors.name ? errors.name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('name')
                                @if(old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email *</label>
                            <input type="email" id="edit_email" name="email" required :value="selectedUser.email" @input="selectedUser.email = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.email" x-text="errors.email ? errors.email[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('email')
                                @if(old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Phone -->
                        <div>
                            <label for="edit_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">No. Telepon</label>
                            <input type="text" id="edit_phone" name="phone" :value="selectedUser.phone" @input="selectedUser.phone = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            <span x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('phone')
                                @if(old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status *</label>
                            <select id="edit_status" name="status" required :value="selectedUser.status" @change="selectedUser.status = $event.target.value"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non-Aktif</option>
                            </select>
                            <span x-show="errors.status" x-text="errors.status ? errors.status[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                            @error('status')
                                @if(old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-900/50">
                        <p class="text-xs text-slate-400 mb-3">Isi bagian di bawah ini hanya jika ingin merubah password pengguna.</p>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- Password -->
                            <div>
                                <label for="edit_password" class="block text-sm font-medium text-slate-750 dark:text-slate-400">Password Baru</label>
                                <input type="password" id="edit_password" name="password" placeholder="Kosongkan jika tetap"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                                <span x-show="errors.password" x-text="errors.password ? errors.password[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                                @error('password')
                                    @if(old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="edit_password_confirmation" class="block text-sm font-medium text-slate-750 dark:text-slate-400">Ulangi Password</label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation" placeholder="Kosongkan jika tetap"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Avatar -->
                    <div x-data="{ avatarPreview: '' }" x-init="$watch('selectedUser.avatar_url', val => avatarPreview = val)">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ubah Foto Avatar</label>
                        <div class="mt-2 flex items-center gap-4">
                            <!-- Image Preview Window -->
                            <div class="h-16 w-16 overflow-hidden rounded-full border border-slate-200 dark:border-slate-700 bg-slate-100 flex items-center justify-center text-slate-400 dark:bg-slate-800">
                                <template x-if="avatarPreview || selectedUser.avatar_url">
                                    <img :src="avatarPreview || selectedUser.avatar_url" alt="Preview" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!avatarPreview && !selectedUser.avatar_url">
                                    <span class="text-sm font-bold" x-text="selectedUser.name ? selectedUser.name.substring(0, 2).toUpperCase() : ''"></span>
                                </template>
                            </div>
                            <!-- Upload Input Button -->
                            <input 
                                type="file" 
                                name="avatar" 
                                accept="image/*"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { avatarPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    } else {
                                        avatarPreview = '';
                                    }
                                "
                                class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950/40 dark:file:text-indigo-400"
                            >
                        </div>
                        <span x-show="errors.avatar" x-text="errors.avatar ? errors.avatar[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        @error('avatar')
                            @if(old('id')) <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @endif
                        @enderror
                    </div>

                    <!-- Footer actions -->
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button type="button" @click="closeEditModal()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RESET PASSWORD MODAL (DIRECT ADMIN) -->
        <div 
            x-show="resetPasswordModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="resetPasswordModalOpen = false"></div>
            
            <div 
                class="relative z-10 w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="resetPasswordModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Reset Password User
                    </h3>
                    <button @click="resetPasswordModalOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-3">
                    <p class="text-sm text-slate-500">
                        Anda akan merubah password untuk user <strong class="text-slate-800 dark:text-white" x-text="selectedUser.name"></strong> secara langsung tanpa email verifikasi.
                    </p>
                </div>

                 <form id="reset-password-form" :action="'/admin/users/' + selectedUser.id + '/reset-password'" method="POST" @submit.prevent="submitForm($event, 'reset')" class="mt-4 space-y-4">
                    @csrf

                    <div>
                        <label for="admin_reset_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password Baru *</label>
                        <input type="password" id="admin_reset_password" name="password" required placeholder="Minimal 8 karakter"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                        <span x-show="errors.password" x-text="errors.password ? errors.password[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <div>
                        <label for="admin_reset_password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ulangi Password Baru *</label>
                        <input type="password" id="admin_reset_password_confirmation" name="password_confirmation" required placeholder="Konfirmasi password"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none dark:border-slate-800 dark:text-white">
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button type="button" @click="resetPasswordModalOpen = false" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE USER CONFIRMATION MODAL -->
        <div 
            x-show="deleteUserModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="deleteUserModalOpen = false"></div>
            
            <div 
                class="relative z-10 w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="deleteUserModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Hapus Pengguna
                    </h3>
                    <button @click="deleteUserModalOpen = false" class="text-slate-400 hover:text-slate-600">
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
                        User <strong x-text="selectedUser.name" class="text-slate-850 dark:text-white"></strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan dan avatar miliknya akan dibersihkan dari penyimpanan.
                    </p>
                </div>

                <form :action="'/admin/users/' + selectedUser.id" method="POST" @submit.prevent="submitForm($event, 'delete')" class="mt-6">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button type="button" @click="deleteUserModalOpen = false" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">
                            Hapus User
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
