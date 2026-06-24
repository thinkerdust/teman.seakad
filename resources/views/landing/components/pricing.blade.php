@php
    /**
     * Generate WhatsApp order URL dengan nama paket yang sudah terisi otomatis.
     * Menggunakan pesan template dari config, tapi kolom "Paket:" diisi otomatis.
     */
    $waNumber = config('services.whatsapp.admin_number', '6281234567890');

    $buildWaUrl = function (string $packageName) use ($waNumber) {
        $message = "Halo Admin Teman Seakad,\n\nSaya tertarik menggunakan layanan Undangan Pernikahan Digital.\n\nNama:\nTanggal Pernikahan:\nPaket: {$packageName}\nJumlah Undangan:\n\nTerima kasih.";
        return 'https://wa.me/' . $waNumber . '?text=' . urlencode($message);
    };
@endphp

<section id="pricing" class="py-24 px-6 md:px-12 bg-[#faf8f5]/60 dark:bg-zinc-900/40 relative">
    <div class="max-w-7xl mx-auto">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-gold-600 dark:text-rose-gold-400 bg-rose-gold-50 dark:bg-rose-gold-950/20 px-3 py-1.5 rounded-full">
                Paket Layanan
            </span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Pilih Paket Sesuai Kebutuhan Pernikahan Anda
            </h2>
            <div class="h-0.5 w-16 bg-rose-gold-400 mx-auto"></div>
            <p class="text-zinc-600 dark:text-zinc-400">
                Pilih dari beberapa pilihan paket harga transparan kami. Mulai secara gratis dan tingkatkan ke premium untuk fitur yang lebih lengkap.
            </p>
        </div>

        <!-- Pricing Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-5xl mx-auto">
            <!-- Free Plan -->
            <div class="bg-white border border-rose-gold-100/35 p-8 rounded-2xl flex flex-col justify-between hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900 transition duration-300">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <h3 class="font-serif text-xl font-bold text-zinc-900 dark:text-white">Paket Gratis</h3>
                        <p class="text-xs text-zinc-500">Cocok untuk mencoba fitur dasar undangan.</p>
                    </div>
                    <div class="flex items-baseline text-zinc-900 dark:text-white">
                        <span class="text-3xl font-serif font-bold">Rp 0</span>
                        <span class="text-xs text-zinc-500 ml-2">/ selamanya</span>
                    </div>
                    <hr class="border-rose-gold-100/30 dark:border-zinc-800">
                    <ul class="space-y-3 text-xs text-zinc-600 dark:text-zinc-400">
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>1 Pilihan Tema Dasar</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Informasi Mempelai & Acara</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Halaman Lokasi Maps</span>
                        </li>
                        <li class="flex items-center gap-2 text-zinc-450 dark:text-zinc-600 line-through">
                            <svg class="h-4 w-4 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            <span>Tanpa Musik Latar Belakang</span>
                        </li>
                        <li class="flex items-center gap-2 text-zinc-450 dark:text-zinc-600 line-through">
                            <svg class="h-4 w-4 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            <span>Tanpa Upload Galeri Foto</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8">
                    <a href="{{ $buildWaUrl('Paket Gratis') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 py-3 px-6 rounded-xl text-xs font-bold border border-rose-gold-300 text-rose-gold-600 hover:bg-rose-gold-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800 transition duration-300">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Mulai Gratis
                    </a>
                </div>
            </div>

            <!-- Premium Plan (Featured) -->
            <div class="relative bg-white border-2 border-rose-gold-400 p-8 rounded-2xl flex flex-col justify-between shadow-xl shadow-rose-gold-500/5 dark:bg-zinc-900 transition duration-300 transform scale-100 lg:scale-[1.03] z-10">
                <!-- Ribbon -->
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-rose-gold-500 to-rose-gold-600 text-white text-[10px] font-bold tracking-wider uppercase shadow-md">
                    Paling Populer
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <h3 class="font-serif text-xl font-bold text-zinc-900 dark:text-white">Paket Premium</h3>
                        <p class="text-xs text-rose-gold-600 dark:text-rose-gold-400">Pilihan terbaik untuk pernikahan sempurna.</p>
                    </div>
                    <div class="flex items-baseline text-zinc-900 dark:text-white">
                        <span class="text-4xl font-serif font-bold">Rp 149.000</span>
                        <span class="text-xs text-zinc-500 ml-2">/ sekali bayar</span>
                    </div>
                    <hr class="border-rose-gold-150/40 dark:border-zinc-800">
                    <ul class="space-y-3 text-xs text-zinc-600 dark:text-zinc-400">
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Semua Pilihan Tema Premium</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Galeri Foto & Kisah Cinta</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Musik Latar Belakang Romantis</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Fitur RSVP Tamu & Pesan Ucapan</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Masa Aktif Selamanya</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8">
                    <a href="{{ $buildWaUrl('Paket Premium') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md shadow-green-500/10 hover:shadow-lg transition duration-300">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Pilih Premium
                    </a>
                </div>
            </div>

            <!-- Custom Plan -->
            <div class="bg-white border border-rose-gold-100/35 p-8 rounded-2xl flex flex-col justify-between hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900 transition duration-300">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <h3 class="font-serif text-xl font-bold text-zinc-900 dark:text-white">Paket Kustom</h3>
                        <p class="text-xs text-zinc-500">Untuk kebutuhan desain eksklusif khusus.</p>
                    </div>
                    <div class="flex items-baseline text-zinc-900 dark:text-white">
                        <span class="text-3xl font-serif font-bold">Hubungi Kami</span>
                    </div>
                    <hr class="border-rose-gold-100/30 dark:border-zinc-800">
                    <ul class="space-y-3 text-xs text-zinc-600 dark:text-zinc-400">
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Semua Fitur Paket Premium</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Desain Tema Khusus (Custom Layout)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Prioritas Bantuan Customer Service</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Integrasi Layanan Khusus</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8">
                    <a href="{{ $buildWaUrl('Paket Kustom') }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 py-3 px-6 rounded-xl text-xs font-bold border border-rose-gold-300 text-rose-gold-600 hover:bg-rose-gold-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800 transition duration-300">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
