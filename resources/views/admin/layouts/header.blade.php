<header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1 dark:bg-slate-900 dark:drop-shadow-none border-b border-slate-200 dark:border-slate-800">
    <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11">
        
        <!-- Toggle sidebar button for mobile -->
        <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <button
                class="z-99999 block rounded-md border border-slate-200 bg-white p-1.5 shadow-sm dark:border-slate-700 dark:bg-slate-800 lg:hidden text-slate-600 dark:text-slate-300"
                @click.stop="sidebarToggle = !sidebarToggle"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Search Bar (Placeholder) -->
        <div class="hidden sm:block">
            <form action="#" method="GET">
                <div class="relative">
                    <button class="absolute left-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <input
                        type="text"
                        placeholder="Cari sesuatu..."
                        class="w-full bg-transparent pl-9 pr-4 text-sm font-medium text-slate-800 focus:outline-none dark:text-white"
                    />
                </div>
            </form>
        </div>

        <!-- Right Side Icons & Profile -->
        <div class="flex items-center gap-3 2xsm:gap-7">
            <ul class="flex items-center gap-2 2xsm:gap-4">
                <!-- Notification Bell -->
                <li class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                    <button
                        class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                        @click="dropdownOpen = !dropdownOpen"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($unreadNotificationCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 z-1 flex h-2.5 w-2.5 rounded-full bg-rose-500">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
                            </span>
                        @endif
                    </button>

                    <!-- Notification Dropdown Menu -->
                    <div
                        x-show="dropdownOpen"
                        x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-4 flex w-80 flex-col rounded-lg border border-slate-200 bg-white shadow-lg dark:border-slate-800 dark:bg-slate-900"
                    >
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                            <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Notifikasi</h3>
                            @if($unreadNotificationCount > 0)
                                <span class="rounded bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                                    {{ $unreadNotificationCount }} Baru
                                </span>
                            @endif
                        </div>

                        <ul class="flex max-h-64 flex-col overflow-y-auto p-2">
                            @forelse($notifications as $notification)
                                <li class="group relative rounded-md p-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150">
                                    <div class="flex flex-col gap-1 pr-6">
                                        <span class="text-xs font-semibold text-slate-800 dark:text-white">
                                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                                        </span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                            {{ $notification->data['message'] ?? '' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <!-- Mark as Read Button -->
                                    <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST" class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-indigo-950/30 dark:hover:text-indigo-400" title="Tandai sudah dibaca">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                </li>
                            @empty
                                <li class="py-6 text-center text-xs text-slate-400 dark:text-slate-500">
                                    Tidak ada notifikasi baru
                                </li>
                            @endforelse
                        </ul>

                        <div class="flex items-center justify-between border-t border-slate-100 p-2 dark:border-slate-800 text-xs">
                            @if($unreadNotificationCount > 0)
                                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="w-1/2 m-0">
                                    @csrf
                                    <button type="submit" class="w-full text-left font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white px-2 py-1.5 rounded transition bg-transparent border-0 cursor-pointer">
                                        Tandai Semua Dibaca
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.notifications.index') }}" class="w-1/2 text-right font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 px-2 py-1.5 rounded transition">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Dark Mode Toggle -->
                <li>
                    <button
                        class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                        @click="darkMode = !darkMode"
                    >
                        <!-- Sun Icon for Dark Mode -->
                        <svg x-show="darkMode" class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464-4.95a1 1 0 111.414 1.414L14.14 7.25a1 1 0 01-1.414-1.414l.793-.793zM18 10a1 1 0 01-1 1h-1a1 1 0 11-2 0h1a1 1 0 011-1zm-4.95 4.95a1 1 0 111.414 1.414l-.793.793a1 1 0 01-1.414-1.414l.793-.793zM5 10a1 1 0 011-1h1a1 1 0 110 2H6a1 1 0 01-1-1zm.464-4.95a1 1 0 111.414 1.414l-.793.793A1 1 0 014.828 5.757l.793-.793zm0 9.9a1 1 0 111.414 1.414l-.793.793a1 1 0 01-1.414-1.414l.793-.793z" clip-rule="evenodd" />
                        </svg>
                        <!-- Moon Icon for Light Mode -->
                        <svg x-show="!darkMode" class="h-5 w-5 text-slate-700 dark:text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>
                </li>
            </ul>

            <!-- User Area -->
            <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                <a
                    class="flex items-center gap-4"
                    href="#"
                    @click.prevent="dropdownOpen = !dropdownOpen"
                >
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-semibold text-slate-800 dark:text-white leading-tight">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="block text-xs font-medium text-slate-500 dark:text-slate-400">
                            Administrator
                        </span>
                    </span>

                    <div class="h-10 w-10 overflow-hidden rounded-full border border-slate-200 dark:border-slate-700 flex items-center justify-center bg-indigo-600 text-white font-bold text-base">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                </a>

                <!-- User Dropdown Menu -->
                <div
                    x-show="dropdownOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-4 flex w-56 flex-col rounded-lg border border-slate-200 bg-white shadow-lg dark:border-slate-800 dark:bg-slate-900"
                >
                    <div class="border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                        <p class="text-xs font-medium text-slate-400 dark:text-slate-500">Logged in as</p>
                        <p class="truncate text-sm font-semibold text-slate-800 dark:text-white">{{ Auth::user()->email }}</p>
                    </div>

                    <ul class="flex flex-col border-b border-slate-100 p-2 dark:border-slate-800">
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3.5 rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition duration-150"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profil Saya
                            </a>
                        </li>
                    </ul>

                    <div class="p-2">
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <button
                            type="button"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex w-full items-center gap-3.5 rounded-md px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition duration-150"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
