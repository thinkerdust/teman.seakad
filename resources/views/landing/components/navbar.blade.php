<nav 
    id="main-navbar"
    x-data="{ mobileMenuOpen: false }"
    class="fixed top-0 left-0 right-0 z-50 py-5 px-6 md:px-12 flex items-center justify-between border-b border-transparent bg-transparent backdrop-blur-[2px]"
>
    <!-- Logo -->
    <a href="#" class="flex items-center gap-2 group">
        <span class="font-serif text-2xl font-bold tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-rose-gold-600 via-rose-gold-500 to-champagne-600 dark:from-rose-gold-400 dark:to-champagne-400 group-hover:opacity-90 transition-opacity">
            Teman Seakad
        </span>
    </a>

    <!-- Desktop Navigation Links -->
    <div class="hidden lg:flex items-center gap-8">
        <a href="#" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">Home</a>
        <a href="#features" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">Fitur</a>
        <a href="#preview" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">Pratinjau</a>
        <a href="#themes" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">Tema</a>
        <a href="#showcase" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">Galeri</a>
        <a href="#workflow" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">Cara Kerja</a>
        <a href="#pricing" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">Harga</a>
        <a href="#faq" class="text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-400 transition-colors">FAQ</a>
    </div>

    <!-- Auth Actions (Desktop) -->
    <div class="hidden lg:flex items-center gap-4">
        @auth
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 rounded-full text-sm font-semibold border border-rose-gold-200 text-rose-gold-600 hover:bg-rose-gold-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800 transition duration-300">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-zinc-600 hover:text-rose-gold-500 dark:text-zinc-300 dark:hover:text-rose-gold-400 transition-colors">
                Masuk
            </a>
            <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full text-sm font-bold bg-gradient-to-r from-rose-gold-500 to-rose-gold-600 text-white shadow-md shadow-rose-gold-500/15 hover:shadow-xl hover:shadow-rose-gold-500/25 hover:scale-[1.03] transition-all duration-300">
                Buat Undangan
            </a>
        @endauth
    </div>

    <!-- Mobile Hamburger Toggle (Morphing Icon) -->
    <button 
        x-on:click="mobileMenuOpen = !mobileMenuOpen" 
        class="lg:hidden text-zinc-700 dark:text-zinc-200 focus:outline-none p-2 relative w-10 h-10 flex flex-col justify-center items-center gap-1.5"
        aria-label="Toggle menu"
    >
        <span :class="mobileMenuOpen ? 'rotate-45 translate-y-[8px]' : ''" class="w-6 h-0.5 bg-current transition-all duration-300 transform origin-center"></span>
        <span :class="mobileMenuOpen ? 'opacity-0' : ''" class="w-6 h-0.5 bg-current transition-all duration-300"></span>
        <span :class="mobileMenuOpen ? '-rotate-45 -translate-y-[8px]' : ''" class="w-6 h-0.5 bg-current transition-all duration-300 transform origin-center"></span>
    </button>

    <!-- Mobile Dropdown Menu -->
    <div 
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 -translate-y-6"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-6"
        class="absolute top-full left-0 right-0 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md border-b border-zinc-200/50 dark:border-zinc-800 shadow-2xl px-6 py-8 flex flex-col gap-4 lg:hidden"
    >
        <a href="#" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">Home</a>
        <a href="#features" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">Fitur</a>
        <a href="#preview" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">Pratinjau</a>
        <a href="#themes" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">Tema</a>
        <a href="#showcase" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">Galeri</a>
        <a href="#workflow" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">Cara Kerja</a>
        <a href="#pricing" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">Harga</a>
        <a href="#faq" x-on:click="mobileMenuOpen = false" class="text-base font-semibold text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500 transition-colors">FAQ</a>
        
        <hr class="border-zinc-200 dark:border-zinc-800/80 my-2">
        
        <div class="flex flex-col gap-3">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="w-full text-center px-5 py-3 rounded-xl text-sm font-semibold border border-rose-gold-200 text-rose-gold-600 dark:border-zinc-700 dark:text-zinc-200 hover:bg-rose-gold-50 dark:hover:bg-zinc-800 transition duration-300">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full text-center px-5 py-3 text-sm font-semibold text-zinc-700 dark:text-zinc-250 hover:text-rose-gold-500 transition duration-300">
                    Masuk
                </a>
                <a href="{{ route('login') }}" class="w-full text-center px-5 py-3 rounded-xl text-sm font-bold bg-gradient-to-r from-rose-gold-500 to-rose-gold-600 text-white shadow-md shadow-rose-gold-500/10 transition duration-300">
                    Buat Undangan
                </a>
            @endauth
        </div>
    </div>
</nav>
