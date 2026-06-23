@extends('admin.layouts.app')

@section('title', 'Kelola Tamu — ' . $invitation->title)

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb 
        pageTitle="Kelola Tamu" 
        :items="[
            'Daftar Undangan' => route('admin.invitations.index'),
            'Kelola Tamu' => ''
        ]" 
    />

    <!-- Main Container -->
    <div 
        x-data="guestsManager({
            hasErrors: @json($errors->any()),
            oldId: '{{ old('id', '') }}',
            oldName: '{{ old('name', '') }}',
            oldPhone: '{{ old('phone', '') }}',
            oldAttendance: '{{ old('attendance', 'hadir') }}',
            oldMessage: '{{ old('message', '') }}',
            invitationSlug: '{{ $invitation->slug }}'
        })"
    >
        <!-- Card Wrapper -->
        <div id="guests-table-container">
            <x-admin.card>
                <x-slot:header>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                                Daftar Tamu: {{ $invitation->title }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                Kelola undangan personal, impor daftar tamu, ekspor CSV, dan pantau status kehadiran RSVP.
                            </p>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Export CSV Button -->
                            <a 
                                href="{{ route('admin.invitations.guests.export', $invitation->id) }}"
                                class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-650 shadow-sm hover:bg-slate-50 transition dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            >
                                <svg class="h-4.5 w-4.5 text-slate-450" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Ekspor CSV
                            </a>

                            <!-- Import CSV Button -->
                            <button 
                                @click="importModalOpen = true"
                                class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-650 shadow-sm hover:bg-slate-50 transition dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            >
                                <svg class="h-4.5 w-4.5 text-slate-450" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Impor CSV
                            </button>

                            <!-- Add Guest Button -->
                            @if(auth()->user()->hasPermission('invitation.update'))
                            <button 
                                @click="createModalOpen = true"
                                class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                            >
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Tamu
                            </button>
                            @endif
                        </div>
                    </div>
                </x-slot:header>

                <!-- Filters -->
                <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <!-- Search Form -->
                    <form action="{{ route('admin.invitations.guests.index', $invitation->id) }}" method="GET" class="flex-grow max-w-md">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Cari nama tamu atau nomor telepon..."
                                class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            @if(request('search') || request('attendance'))
                                <a href="{{ route('admin.invitations.guests.index', $invitation->id) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-rose-500 hover:underline">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Status Filter Dropdown -->
                    <form id="filter-form" action="{{ route('admin.invitations.guests.index', $invitation->id) }}" method="GET" class="flex items-center gap-3">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <label for="attendance-filter" class="text-sm font-medium text-slate-500 dark:text-slate-400">Kehadiran:</label>
                        <select 
                            id="attendance-filter" 
                            name="attendance" 
                            onchange="document.getElementById('filter-form').submit()"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Semua</option>
                            <option value="hadir" {{ request('attendance') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="tidak_hadir" {{ request('attendance') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                            <option value="belum_pasti" {{ request('attendance') == 'belum_pasti' ? 'selected' : '' }}>Belum Pasti</option>
                        </select>
                    </form>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-800">
                    <table class="w-full border-collapse text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:bg-slate-900/50 dark:text-slate-350">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nama Tamu</th>
                                <th scope="col" class="px-6 py-4">Nomor Telepon</th>
                                <th scope="col" class="px-6 py-4">Kehadiran</th>
                                <th scope="col" class="px-6 py-4">Pesan / Ucapan</th>
                                <th scope="col" class="px-6 py-4">Link Undangan Personal</th>
                                <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($guests as $guest)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800 dark:text-white">
                                            {{ $guest->name }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-xs font-mono text-slate-600 dark:text-slate-400">
                                        {{ $guest->phone ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($guest->attendance === 'hadir')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                                Hadir
                                            </span>
                                        @elseif($guest->attendance === 'tidak_hadir')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-950/30 dark:text-rose-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-600 dark:bg-rose-400"></span>
                                                Tidak Hadir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-600 dark:bg-amber-400"></span>
                                                Belum Pasti
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate" title="{{ $guest->message }}">
                                        {{ $guest->message ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-xs">
                                        <div class="flex items-center gap-1.5">
                                            <code class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-xxs font-mono text-slate-500 dark:text-slate-400 select-all max-w-[200px] truncate" x-text="getPersonalLink('{{ $guest->name }}')"></code>
                                            <button 
                                                @click="copyPersonalLink('{{ $guest->name }}')"
                                                class="text-indigo-500 hover:text-indigo-600 font-semibold flex items-center gap-0.5 transition"
                                                title="Salin Tautan Personal"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-5 10h5m-5-4h5m-5-4h5" />
                                                </svg>
                                                Salin
                                            </button>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Edit Button -->
                                            @if(auth()->user()->hasPermission('invitation.update'))
                                                <button 
                                                    @click="editGuest({{ json_encode($guest) }})"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                                    title="Edit Data Tamu"
                                                >
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Delete Button -->
                                            @if(auth()->user()->hasPermission('invitation.delete'))
                                                <button 
                                                    @click="confirmDelete({{ json_encode($guest) }})"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-rose-500 hover:bg-rose-50 dark:border-slate-800 dark:bg-slate-900 dark:text-rose-500 dark:hover:bg-rose-950/20"
                                                    title="Hapus Tamu"
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
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                        <svg class="mx-auto h-12 w-12 text-slate-350 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>
                                        <h4 class="mt-4 font-semibold text-slate-800 dark:text-white">Tidak Ada Tamu</h4>
                                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Belum ada tamu terdaftar untuk undangan ini. Klik Tambah Tamu untuk memulai.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-6">
                    {{ $guests->links() }}
                </div>
            </x-admin.card>
        </div>

        <!-- CREATE GUEST MODAL -->
        @if(auth()->user()->hasPermission('invitation.update'))
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
                        Tambah Tamu Baru
                    </h3>
                    <button @click="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form 
                    id="create-guest-form"
                    action="{{ route('admin.invitations.guests.store', $invitation->id) }}" 
                    method="POST" 
                    @submit.prevent="submitForm($event, 'create')"
                    class="mt-4 space-y-4"
                >
                    @csrf
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap Tamu <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="selectedGuest.name"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.name" x-text="errors.name ? errors.name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Telepon</label>
                        <input 
                            type="text" 
                            name="phone" 
                            x-model="selectedGuest.phone"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Attendance -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status Kehadiran <span class="text-rose-500">*</span></label>
                        <select 
                            name="attendance" 
                            x-model="selectedGuest.attendance"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="hadir">Hadir</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                            <option value="belum_pasti">Belum Pasti</option>
                        </select>
                        <span x-show="errors.attendance" x-text="errors.attendance ? errors.attendance[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pesan / Ucapan</label>
                        <textarea 
                            name="message" 
                            x-model="selectedGuest.message"
                            rows="3"
                            placeholder="Tulis ucapan selamat atau doa restu dari tamu..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <span x-show="errors.message" x-text="errors.message ? errors.message[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
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

        <!-- EDIT GUEST MODAL -->
        @if(auth()->user()->hasPermission('invitation.update'))
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
                        Ubah Data Tamu
                    </h3>
                    <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form 
                    id="edit-guest-form"
                    :action="`{{ url('/admin/invitations/' . $invitation->id . '/guests') }}/${selectedGuest.id}`" 
                    method="POST" 
                    @submit.prevent="submitForm($event, 'edit')"
                    class="mt-4 space-y-4"
                >
                    @csrf
                    @method('PUT')
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap Tamu <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="selectedGuest.name"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.name" x-text="errors.name ? errors.name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Telepon</label>
                        <input 
                            type="text" 
                            name="phone" 
                            x-model="selectedGuest.phone"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.phone" x-text="errors.phone ? errors.phone[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Attendance -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status Kehadiran <span class="text-rose-500">*</span></label>
                        <select 
                            name="attendance" 
                            x-model="selectedGuest.attendance"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="hadir">Hadir</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                            <option value="belum_pasti">Belum Pasti</option>
                        </select>
                        <span x-show="errors.attendance" x-text="errors.attendance ? errors.attendance[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pesan / Ucapan</label>
                        <textarea 
                            name="message" 
                            x-model="selectedGuest.message"
                            rows="3"
                            placeholder="Tulis ucapan selamat atau doa restu dari tamu..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <span x-show="errors.message" x-text="errors.message ? errors.message[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button 
                            type="button" 
                            @click="closeEditModal()"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-650 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
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

        <!-- IMPORT CSV MODAL -->
        <div 
            x-show="importModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="importModalOpen = false"></div>
            
            <!-- Modal Body -->
            <div 
                class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                x-show="importModalOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Impor Tamu via CSV
                    </h3>
                    <button @click="importModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form 
                    id="import-guests-form"
                    action="{{ route('admin.invitations.guests.import', $invitation->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    @submit.prevent="submitForm($event, 'import')"
                    class="mt-4 space-y-4"
                >
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">File CSV <span class="text-rose-500">*</span></label>
                        <input 
                            type="file" 
                            name="csv_file" 
                            accept=".csv,.txt"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-slate-800"
                        />
                        <span x-show="errors.csv_file" x-text="errors.csv_file ? errors.csv_file[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <div class="rounded-xl bg-amber-50 p-4 border border-amber-150 text-xs text-amber-800 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-400 leading-relaxed">
                        <strong class="font-bold block mb-1">Panduan Format CSV:</strong>
                        <ul class="list-disc pl-4 space-y-1">
                            <li>Format file harus berakhiran <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded">.csv</code>.</li>
                            <li>Kolom baris pertama adalah header: <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded">Nama Tamu, Nomor Telepon, Kehadiran, Pesan</code>.</li>
                            <li>Kehadiran diisi salah satu: <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded">hadir</code>, <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded">tidak hadir</code>, atau <code class="font-mono bg-white dark:bg-slate-800 px-1 py-0.5 rounded">belum pasti</code>.</li>
                        </ul>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <button 
                            type="button" 
                            @click="importModalOpen = false"
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
                            Mulai Impor
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE CONFIRMATION MODAL -->
        @if(auth()->user()->hasPermission('invitation.delete'))
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
                        Hapus Tamu dari Undangan?
                    </h3>
                    
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus tamu <strong class="text-slate-700 dark:text-slate-200 font-semibold" x-text="selectedGuest.name"></strong>? Tindakan ini bersifat permanen dan konfirmasi kehadirannya akan dihapus dari sistem.
                    </p>
                </div>

                <!-- Form Hapus -->
                <form 
                    :action="`{{ url('/admin/invitations/' . $invitation->id . '/guests') }}/${selectedGuest.id}`" 
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
                        Hapus Tamu
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
@endsection
