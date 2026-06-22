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
