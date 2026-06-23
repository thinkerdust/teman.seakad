@extends('admin.layouts.app')

@section('title', 'Invitation Management')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Invitation Management" :items="['Invitation Management' => '']" />

    <!-- Main Container -->
    <div 
        x-data="invitationsManager({
            hasErrors: @json($errors->any()),
            oldId: '{{ old('id', '') }}',
            oldThemeId: '{{ old('theme_id', '') }}',
            oldTitle: '{{ old('title', '') }}',
            oldSlug: '{{ old('slug', '') }}',
            oldGroomName: '{{ old('groom_name', '') }}',
            oldBrideName: '{{ old('bride_name', '') }}',
            oldAkadDate: '{{ old('akad_date', '') }}',
            oldReceptionDate: '{{ old('reception_date', '') }}',
            oldVenue: '{{ old('venue', '') }}',
            oldAddress: '{{ old('address', '') }}',
            oldMapsUrl: '{{ old('maps_url', '') }}',
            oldDescription: '{{ old('description', '') }}',
            oldStatus: '{{ old('status', 'draft') }}'
        })"
    >
        <!-- Card Wrapper -->
        <div id="invitations-table-container">
            <x-admin.card>
                <x-slot:header>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                            Daftar Undangan Digital
                        </h3>
                        
                        @if(auth()->user()->hasPermission('invitation.create'))
                        <button 
                            @click="createModalOpen = true"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                        >
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Undangan
                        </button>
                        @endif
                    </div>
                </x-slot:header>

                <!-- Filters -->
                <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <!-- Search Form -->
                    <form action="{{ route('admin.invitations.index') }}" method="GET" class="flex-grow max-w-md">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Cari judul, slug, mempelai pria/wanita..."
                                class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.invitations.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-rose-500 hover:underline">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Status Filter Dropdown -->
                    <form id="filter-form" action="{{ route('admin.invitations.index') }}" method="GET" class="flex items-center gap-3">
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
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired / Nonaktif</option>
                        </select>
                    </form>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-800">
                    <table class="w-full border-collapse text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:bg-slate-900/50 dark:text-slate-350">
                            <tr>
                                <th scope="col" class="px-6 py-4">Judul & Slug</th>
                                @if(auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@teman-seakad.com')
                                    <th scope="col" class="px-6 py-4">Pemilik (User)</th>
                                @endif
                                <th scope="col" class="px-6 py-4">Tema</th>
                                <th scope="col" class="px-6 py-4">Mempelai</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Masa Aktif</th>
                                <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($invitations as $invitation)
                                @php
                                    $isExpired = false;
                                    if ($invitation->status === 'expired') {
                                        $isExpired = true;
                                    } elseif ($invitation->status === 'published' && $invitation->expired_at && $invitation->expired_at->isPast()) {
                                        $isExpired = true;
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800 dark:text-white">
                                            {{ $invitation->title }}
                                        </div>
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <code class="text-xxs font-mono text-slate-400 dark:text-slate-500">
                                                /{{ $invitation->slug }}
                                            </code>
                                            @if($invitation->status === 'published' && !$isExpired)
                                                <a href="{{ url('/' . $invitation->slug) }}" target="_blank" class="text-xxs text-indigo-500 hover:underline flex items-center gap-0.5">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    Kunjungi
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    @if(auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@teman-seakad.com')
                                        <td class="px-6 py-4 text-xs">
                                            <div class="font-medium text-slate-700 dark:text-slate-350">{{ $invitation->user?->name ?: 'N/A' }}</div>
                                            <div class="text-slate-400 mt-0.5">{{ $invitation->user?->email ?: '-' }}</div>
                                        </td>
                                    @endif

                                    <td class="px-6 py-4 text-xs">
                                        <span class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-300">
                                            <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                            {{ $invitation->theme?->name ?: 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-xs">
                                        <div class="font-medium text-slate-700 dark:text-slate-350">
                                            👨 {{ $invitation->groom_name }}
                                        </div>
                                        <div class="font-medium text-slate-700 dark:text-slate-350 mt-1">
                                            👩 {{ $invitation->bride_name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($isExpired)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-950/30 dark:text-rose-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-600 dark:bg-rose-400"></span>
                                                Expired
                                            </span>
                                        @elseif($invitation->status === 'published')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-600 dark:bg-amber-400"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                        @if($invitation->status === 'published' && $invitation->expired_at)
                                            <div class="font-medium">{{ $invitation->expired_at->format('d M Y') }}</div>
                                            <div class="text-xxs text-slate-400 mt-0.5">({{ $invitation->expired_at->diffForHumans() }})</div>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Publish / Disable Toggle Button -->
                                            @if(auth()->user()->hasPermission('invitation.update'))
                                                <button 
                                                    @click="toggleStatusAjax('{{ route('admin.invitations.toggle-status', $invitation->id) }}')"
                                                    :disabled="loading"
                                                    class="inline-flex h-8 px-2.5 items-center justify-center rounded-lg border text-xs font-semibold transition disabled:opacity-50
                                                        @if($invitation->status === 'published')
                                                            border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-800 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white
                                                        @else
                                                            border-indigo-100 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:border-indigo-950/50 dark:bg-indigo-950/30 dark:text-indigo-400 dark:hover:bg-indigo-900/40
                                                        @endif"
                                                    title="{{ $invitation->status === 'published' ? 'Kembalikan ke Draft (Disable)' : 'Terbitkan Undangan (Publish)' }}"
                                                >
                                                    @if($invitation->status === 'published')
                                                        Disable
                                                    @else
                                                        Publish
                                                    @endif
                                                </button>
                                            @endif

                                            <!-- Kelola Tamu Button -->
                                            <a 
                                                href="{{ route('admin.invitations.guests.index', $invitation->id) }}"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                                title="Kelola Tamu"
                                            >
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </a>

                                            <!-- Kelola Konten Button -->
                                            @if(auth()->user()->hasPermission('invitation.update'))
                                                <a 
                                                    href="{{ route('admin.invitations.content.edit', $invitation->id) }}"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                                    title="Kelola Konten"
                                                >
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </a>
                                            @endif

                                            <!-- Edit Button -->
                                            @if(auth()->user()->hasPermission('invitation.update'))
                                                <button 
                                                    @click="editInvitation({{ json_encode($invitation) }})"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                                    title="Edit Undangan"
                                                >
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Delete Button -->
                                            @if(auth()->user()->hasPermission('invitation.delete'))
                                                <button 
                                                    @click="confirmDelete({{ json_encode($invitation) }})"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-rose-500 hover:bg-rose-50 dark:border-slate-800 dark:bg-slate-900 dark:text-rose-500 dark:hover:bg-rose-950/20"
                                                    title="Hapus Undangan"
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
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                        <svg class="mx-auto h-12 w-12 text-slate-350 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <h4 class="mt-4 font-semibold text-slate-800 dark:text-white">Tidak Ada Undangan</h4>
                                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Belum ada undangan yang dibuat atau tidak ada data yang cocok dengan kriteria pencarian.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-6">
                    {{ $invitations->links() }}
                </div>
            </x-admin.card>
        </div>

        <!-- CREATE INVITATION MODAL -->
        @if(auth()->user()->hasPermission('invitation.create'))
        <div 
            x-show="createModalOpen" 
            x-cloak
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/50 transition-opacity duration-300" @click="closeCreateModal()"></div>
            
            <!-- Modal Body -->
            <div 
                class="relative z-10 w-full max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
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
                        Buat Undangan Baru
                    </h3>
                    <button @click="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form 
                    id="create-invitation-form"
                    action="{{ route('admin.invitations.store') }}" 
                    method="POST" 
                    @submit.prevent="submitForm($event, 'create')"
                    class="mt-4 max-h-[70vh] overflow-y-auto pr-2 space-y-4"
                >
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Judul Undangan <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="title" 
                                x-model="selectedInvitation.title"
                                placeholder="Contoh: Pernikahan Budi & Ani"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.title" x-text="errors.title ? errors.title[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Slug URL <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="slug" 
                                x-model="selectedInvitation.slug"
                                placeholder="Contoh: budi-ani"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.slug" x-text="errors.slug ? errors.slug[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Theme ID -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Tema <span class="text-rose-500">*</span></label>
                            <select 
                                name="theme_id" 
                                x-model="selectedInvitation.theme_id"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Pilih Tema</option>
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                @endforeach
                            </select>
                            <span x-show="errors.theme_id" x-text="errors.theme_id ? errors.theme_id[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Venue -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tempat Acara (Gedung/Rumah) <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="venue" 
                                x-model="selectedInvitation.venue"
                                placeholder="Contoh: Hotel Mulia Senayan"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.venue" x-text="errors.venue ? errors.venue[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Groom Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Mempelai Pria <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="groom_name" 
                                x-model="selectedInvitation.groom_name"
                                placeholder="Nama lengkap mempelai pria"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.groom_name" x-text="errors.groom_name ? errors.groom_name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Bride Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Mempelai Wanita <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="bride_name" 
                                x-model="selectedInvitation.bride_name"
                                placeholder="Nama lengkap mempelai wanita"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.bride_name" x-text="errors.bride_name ? errors.bride_name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Akad Date -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal & Waktu Akad</label>
                            <input 
                                type="datetime-local" 
                                name="akad_date" 
                                x-model="selectedInvitation.akad_date"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.akad_date" x-text="errors.akad_date ? errors.akad_date[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Reception Date -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal & Waktu Resepsi</label>
                            <input 
                                type="datetime-local" 
                                name="reception_date" 
                                x-model="selectedInvitation.reception_date"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.reception_date" x-text="errors.reception_date ? errors.reception_date[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <!-- Maps URL -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Google Maps URL</label>
                        <input 
                            type="url" 
                            name="maps_url" 
                            x-model="selectedInvitation.maps_url"
                            placeholder="https://maps.google.com/..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.maps_url" x-text="errors.maps_url ? errors.maps_url[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea 
                            name="address" 
                            x-model="selectedInvitation.address"
                            rows="2"
                            placeholder="Tuliskan alamat lengkap lokasi acara..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <span x-show="errors.address" x-text="errors.address ? errors.address[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan Tambahan / Ucapan</label>
                        <textarea 
                            name="description" 
                            x-model="selectedInvitation.description"
                            rows="3"
                            placeholder="Tulis ucapan selamat atau catatan khusus di sini..."
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

        <!-- EDIT INVITATION MODAL -->
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
                class="relative z-10 w-full max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900"
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
                        Ubah Detail Undangan
                    </h3>
                    <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form 
                    id="edit-invitation-form"
                    :action="`{{ url('/admin/invitations') }}/${selectedInvitation.id}`" 
                    method="POST" 
                    @submit.prevent="submitForm($event, 'edit')"
                    class="mt-4 max-h-[70vh] overflow-y-auto pr-2 space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Judul Undangan <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="title" 
                                x-model="selectedInvitation.title"
                                placeholder="Contoh: Pernikahan Budi & Ani"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.title" x-text="errors.title ? errors.title[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Slug URL <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="slug" 
                                x-model="selectedInvitation.slug"
                                placeholder="Contoh: budi-ani"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.slug" x-text="errors.slug ? errors.slug[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Theme ID -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Tema <span class="text-rose-500">*</span></label>
                            <select 
                                name="theme_id" 
                                x-model="selectedInvitation.theme_id"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Pilih Tema</option>
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                @endforeach
                            </select>
                            <span x-show="errors.theme_id" x-text="errors.theme_id ? errors.theme_id[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Venue -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tempat Acara (Gedung/Rumah) <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="venue" 
                                x-model="selectedInvitation.venue"
                                placeholder="Contoh: Hotel Mulia Senayan"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.venue" x-text="errors.venue ? errors.venue[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Groom Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Mempelai Pria <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="groom_name" 
                                x-model="selectedInvitation.groom_name"
                                placeholder="Nama lengkap mempelai pria"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.groom_name" x-text="errors.groom_name ? errors.groom_name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Bride Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Mempelai Wanita <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="bride_name" 
                                x-model="selectedInvitation.bride_name"
                                placeholder="Nama lengkap mempelai wanita"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.bride_name" x-text="errors.bride_name ? errors.bride_name[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Akad Date -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal & Waktu Akad</label>
                            <input 
                                type="datetime-local" 
                                name="akad_date" 
                                x-model="selectedInvitation.akad_date"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.akad_date" x-text="errors.akad_date ? errors.akad_date[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>

                        <!-- Reception Date -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal & Waktu Resepsi</label>
                            <input 
                                type="datetime-local" 
                                name="reception_date" 
                                x-model="selectedInvitation.reception_date"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <span x-show="errors.reception_date" x-text="errors.reception_date ? errors.reception_date[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                        </div>
                    </div>

                    <!-- Maps URL -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Google Maps URL</label>
                        <input 
                            type="url" 
                            name="maps_url" 
                            x-model="selectedInvitation.maps_url"
                            placeholder="https://maps.google.com/..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span x-show="errors.maps_url" x-text="errors.maps_url ? errors.maps_url[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea 
                            name="address" 
                            x-model="selectedInvitation.address"
                            rows="2"
                            placeholder="Tuliskan alamat lengkap lokasi acara..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <span x-show="errors.address" x-text="errors.address ? errors.address[0] : ''" class="text-xs text-rose-500 mt-1 block"></span>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan Tambahan / Ucapan</label>
                        <textarea 
                            name="description" 
                            x-model="selectedInvitation.description"
                            rows="3"
                            placeholder="Tulis ucapan selamat atau catatan khusus di sini..."
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
                        Hapus Undangan Digital?
                    </h3>
                    
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Apakah Anda yakin ingin menghapus undangan <strong class="text-slate-700 dark:text-slate-200 font-semibold" x-text="selectedInvitation.title"></strong>? Tindakan ini bersifat permanen dan seluruh data tamu (RSVP) serta statistik kunjungan undangan ini juga akan dihapus.
                    </p>
                </div>

                <!-- Form Hapus -->
                <form 
                    :action="`{{ url('/admin/invitations') }}/${selectedInvitation.id}`" 
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
                        Hapus Undangan
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
@endsection
