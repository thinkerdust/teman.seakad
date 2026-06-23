<nav 
    x-data="{ isScrolled: false, mobileMenuOpen: false }"
    x-init="window.addEventListener('scroll', () => { isScrolled = window.scrollY > 20 })"
    :class="isScrolled ? 'bg-white/95 dark:bg-zinc-900/95 shadow-md border-b border-rose-gold-100/30 dark:border-zinc-800' : 'bg-transparent border-transparent'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-sm py-4 px-6 md:px-12 flex items-center justify-between"
>
    <!-- Logo -->
    <a href="#" class="flex items-center gap-2">
        <span class="font-serif text-2xl font-bold tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-rose-gold-600 via-rose-gold-500 to-champagne-600 dark:from-rose-gold-400 dark:to-champagne-400">
            Teman Seakad
        </span>
    </a>

    <!-- Desktop Navigation Links -->
    <div class="hidden lg:flex items-center gap-8">
        <a href="#" class="text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-450 transition-colors">Home</a>
        <a href="#features" class="text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-450 transition-colors">Fitur</a>
        <a href="#preview" class="text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-450 transition-colors">Pratinjau</a>
        <a href="#themes" class="text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-450 transition-colors">Tema</a>
        <a href="#workflow" class="text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-450 transition-colors">Cara Kerja</a>
        <a href="#pricing" class="text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-450 transition-colors">Harga</a>
        <a href="#faq" class="text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-rose-gold-500 dark:hover:text-rose-gold-450 transition-colors">FAQ</a>
    </div>

    <!-- Auth Actions (Desktop) -->
    <div class="hidden lg:flex items-center gap-4">
        @auth
            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-full text-sm font-semibold border border-rose-gold-200 text-rose-gold-600 hover:bg-rose-gold-50 transition duration-300 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-zinc-650 hover:text-rose-gold-500 dark:text-zinc-300 dark:hover:text-rose-gold-450 transition-colors">
                Masuk
            </a>
            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full text-sm font-semibold bg-gradient-to-r from-rose-gold-500 to-rose-gold-600 text-white shadow-md shadow-rose-gold-500/10 hover:shadow-lg hover:shadow-rose-gold-500/20 hover:scale-[1.02] transition duration-300">
                Buat Undangan
            </a>
        @endauth
    </div>

    <!-- Mobile Hamburger Toggle -->
    <button 
        x-on:click="mobileMenuOpen = !mobileMenuOpen" 
        class="lg:hidden text-zinc-700 dark:text-zinc-200 focus:outline-none p-2"
        aria-label="Toggle menu"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Mobile Dropdown Menu -->
    <div 
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="absolute top-full left-0 right-0 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 shadow-xl px-6 py-6 flex flex-col gap-4 lg:hidden"
    >
        <a href="#" x-on:click="mobileMenuOpen = false" class="text-base font-medium text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500">Home</a>
        <a href="#features" x-on:click="mobileMenuOpen = false" class="text-base font-medium text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500">Fitur</a>
        <a href="#preview" x-on:click="mobileMenuOpen = false" class="text-base font-medium text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500">Pratinjau</a>
        <a href="#themes" x-on:click="mobileMenuOpen = false" class="text-base font-medium text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500">Tema</a>
        <a href="#workflow" x-on:click="mobileMenuOpen = false" class="text-base font-medium text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500">Cara Kerja</a>
        <a href="#pricing" x-on:click="mobileMenuOpen = false" class="text-base font-medium text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500">Harga</a>
        <a href="#faq" x-on:click="mobileMenuOpen = false" class="text-base font-medium text-zinc-700 dark:text-zinc-200 hover:text-rose-gold-500">FAQ</a>
        
        <hr class="border-zinc-200 dark:border-zinc-800 my-2">
        
        <div class="flex flex-col gap-3">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="w-full text-center px-5 py-3 rounded-xl text-sm font-semibold border border-rose-gold-200 text-rose-gold-600 dark:border-zinc-700 dark:text-zinc-200">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full text-center px-5 py-3 text-sm font-semibold text-zinc-700 dark:text-zinc-200">
                    Masuk
                </a>
                <a href="{{ route('login') }}" class="w-full text-center px-5 py-3 rounded-xl text-sm font-semibold bg-gradient-to-r from-rose-gold-500 to-rose-gold-600 text-white">
                    Buat Undangan
                </a>
            @endauth
        </div>
    </div>
</nav>
