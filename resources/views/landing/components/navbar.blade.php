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
            <a href="{{ $whatsappOrderUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md shadow-green-500/15 hover:shadow-xl hover:shadow-green-500/25 hover:scale-[1.03] transition-all duration-300">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Pesan Undangan
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
                <a href="{{ $whatsappOrderUrl }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md shadow-green-500/10 transition duration-300">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Pesan Undangan
                </a>
            @endauth
        </div>
    </div>
</nav>
