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
            @foreach($sidebarMenus as $group)
                <!-- Menu Group -->
                <div class="mb-6">
                    <h3 class="mb-4 ml-4 text-xs font-semibold text-slate-400 uppercase tracking-wider dark:text-slate-500">
                        {{ $group->title }}
                    </h3>

                    <ul class="flex flex-col gap-1.5">
                        @foreach($group->children as $menu)
                            <li>
                                @php
                                    $hasRoute = $menu->route && Route::has($menu->route);
                                    $url = $hasRoute ? route($menu->route) : ($menu->route ?: '#');
                                    // Match active route by prefix (e.g. admin.users.*)
                                    $isActive = false;
                                    if ($hasRoute) {
                                        $routeParts = explode('.', $menu->route);
                                        $routePrefix = isset($routeParts[1]) ? $routeParts[0] . '.' . $routeParts[1] : $routeParts[0];
                                        $isActive = request()->routeIs($routePrefix . '.*') || request()->routeIs($menu->route);
                                    }
                                @endphp
                                <a 
                                    href="{{ $url }}" 
                                    class="group relative flex items-center gap-2.5 rounded-lg px-4 py-2.5 font-medium text-slate-600 duration-300 ease-in-out hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white {{ $isActive ? 'bg-indigo-50 text-indigo-600 font-semibold dark:bg-slate-900 dark:text-white' : '' }}"
                                >
                                    @if($menu->icon)
                                        <span class="[&>svg]:h-5 [&>svg]:w-5 [&>svg]:text-slate-400 [&>svg]:group-hover:text-indigo-600 [&>svg]:dark:group-hover:text-indigo-400 {{ $isActive ? '[&>svg]:text-indigo-600 [&>svg]:dark:text-indigo-400' : '' }}">
                                            {!! $menu->icon !!}
                                        </span>
                                    @else
                                        <!-- Default Fallback Icon -->
                                        <svg class="h-5 w-5 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    @endif
                                    {{ $menu->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
