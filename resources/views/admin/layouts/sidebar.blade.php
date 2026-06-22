<aside 
    :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
    class="fixed left-0 top-0 z-999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-white border-r border-slate-200 duration-300 ease-linear lg:static lg:translate-x-0 dark:bg-slate-950 dark:border-slate-800"
    @click.outside="sidebarToggle = false"
>
    <!-- SIDEBAR HEADER -->
    <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21l-8.25-7.5A5.25 5.25 0 0112 5.25a5.25 5.25 0 018.25 8.25L12 21z" />
            </svg>
            <span class="text-xl font-bold text-slate-800 tracking-wider dark:text-white">Teman Seakad</span>
        </a>

        <button
            class="block lg:hidden text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white"
            @click.stop="sidebarToggle = !sidebarToggle"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <!-- Sidebar Menu -->
        <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6">
            <!-- Menu Group 1 -->
            <div>
                <h3 class="mb-4 ml-4 text-xs font-semibold text-slate-400 uppercase tracking-wider dark:text-slate-500">
                    Menu Utama
                </h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Dashboard -->
                    <li>
                        <a 
                            href="{{ route('admin.dashboard') }}" 
                            class="group relative flex items-center gap-2.5 rounded-lg px-4 py-2.5 font-medium text-slate-600 duration-300 ease-in-out hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold dark:bg-slate-900 dark:text-white' : '' }}"
                        >
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    <!-- Users Management -->
                    <li>
                        <a 
                            href="{{ route('admin.users.index') }}" 
                            class="group relative flex items-center gap-2.5 rounded-lg px-4 py-2.5 font-medium text-slate-600 duration-300 ease-in-out hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 font-semibold dark:bg-slate-900 dark:text-white' : '' }}"
                        >
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 {{ request()->routeIs('admin.users.*') ? 'text-indigo-600 dark:text-indigo-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            User Management
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Menu Group 2 -->
            <div>
                <h3 class="mb-4 ml-4 text-xs font-semibold text-slate-400 uppercase tracking-wider dark:text-slate-500">
                    Fitur Undangan
                </h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Themes -->
                    <li>
                        <a 
                            href="#" 
                            class="group relative flex items-center gap-2.5 rounded-lg px-4 py-2.5 font-medium text-slate-600 duration-300 ease-in-out hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white"
                        >
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tema Undangan
                        </a>
                    </li>

                    <!-- Invitations -->
                    <li>
                        <a 
                            href="#" 
                            class="group relative flex items-center gap-2.5 rounded-lg px-4 py-2.5 font-medium text-slate-600 duration-300 ease-in-out hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white"
                        >
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5M12 14v4h-.01" />
                            </svg>
                            Daftar Undangan
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
