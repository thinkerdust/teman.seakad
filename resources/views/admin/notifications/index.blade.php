@extends('admin.layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Notifikasi" :items="['Notifikasi' => '']" />

    <!-- Main Container -->
    <div class="space-y-6">
        <!-- Notification Card -->
        <x-admin.card>
            <x-slot:header>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                            Semua Notifikasi
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">
                            Lihat dan kelola semua pemberitahuan sistem untuk akun Anda.
                        </p>
                    </div>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <div>
                            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button 
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150 border-0 cursor-pointer"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Tandai Semua Dibaca
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </x-slot:header>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($notifications as $notification)
                    <div class="py-4 first:pt-0 last:pb-0 flex items-start justify-between gap-4 {{ is_null($notification->read_at) ? 'bg-indigo-50/20 dark:bg-indigo-950/5 -mx-6 px-6 border-l-4 border-indigo-600' : '' }}">
                        <div class="flex-grow space-y-1">
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </h4>
                                @if(is_null($notification->read_at))
                                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                                        Baru
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <div class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>

                        @if(is_null($notification->read_at))
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button 
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 cursor-pointer"
                                        title="Tandai sudah dibaca"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tandai Dibaca
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 dark:text-slate-500">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 text-slate-400 dark:bg-slate-900 dark:text-slate-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-800 dark:text-white">Tidak ada notifikasi</h4>
                                <p class="text-xs text-slate-400 mt-1">Anda tidak memiliki notifikasi apa pun saat ini.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </x-admin.card>
    </div>
@endsection
