# Theme Preview & Experience Analysis

Analisis mendalam mengenai alur peninjauan tema (Theme Preview Flow) dan pengoptimalan pengalaman akhir bagi pengguna (*Final Experience Optimization*).

---

## 1. Alur Peninjauan Tema Saat Ini (Current Preview Flow)

Saat ini, pratinjau tema diakses melalui rute `/theme-preview/{slug}` yang dikendalikan oleh `ThemeController@preview`.

### Masalah/Temuan Utama:
1. **Data Dummy Hardcoded di Controller**: Logika pembentukan data tiruan (nama, galeri, cerita, acara, musik) ditulis secara langsung (*hardcoded*) di dalam `ThemeController@preview`. Hal ini menyalahi prinsip Single Responsibility karena controller harusnya hanya mengalirkan request.
2. **Page Load Kasar (No Loading Screen)**: Halaman langsung memuat seluruh elemen HTML secara instan. Pada koneksi lambat, browser akan merender stylesheet secara parsial dan memperlihatkan halaman yang berantakan sebelum ornamen & gambar dimuat penuh.
3. **Scroll Bebas Sebelum Dibuka**: Tamu dapat melakukan scroll ke bawah melihat isi undangan walaupun cover penutup (`#cover-overlay`) belum diklik. Ini merusak nuansa kejutan (*cover entrance experience*).
4. **Kebijakan Autoplay Browser Modern**: Musik latar seringkali gagal diputar otomatis jika browser memblokir aksi autoplay tanpa interaksi pengguna terlebih dahulu.
5. **Kurangnya Smooth Scroll**: Pindah antar section (misal melalui navigasi manual) terasa kaku dan melompat-lompat (*jumpy scroll*).

---

## 2. Rencana Peningkatan (Improvement Plan)

Untuk mewujudkan pengalaman digital premium yang mulus, rencana peningkatan berikut akan diimplementasikan:

1. **Membuat Dedicated Provider Service (`ThemePreviewService`)**:
   - Memindahkan generator data tiruan ke `ThemePreviewService`.
   - Data tiruan yang dibuat akan menggunakan format nama standardisasi: **Ayu & Rakhmadani**, tertanggal **10 Oktober 2026** (Lembah Hijau Sentul).
2. **Layar Loading Premium (Loading Screen)**:
   - Menambahkan overlay loading screen dengan spinner animasi hati/cincin estetis yang diselimuti backdrop-blur.
   - Layar loading akan di-fade out halus menggunakan GSAP setelah semua aset selesai dimuat (`window.addEventListener('load')`).
3. **Scroll Lock & Unlocked Flow**:
   - Memasang kelas `overflow-hidden` secara default pada `<body>` saat awal masuk halaman.
   - Menghapus kelas `overflow-hidden` hanya setelah tombol "Buka Undangan" diklik oleh pengguna.
4. **State Recovery & Autoplay Musik**:
   - Pemutar musik akan menyimpan status terakhir (`music_playing`) di `localStorage`.
   - Jika tamu memuat ulang halaman yang sudah dibuka sebelumnya, sistem akan mendengarkan aktivitas interaksi klik perdana tamu di layar untuk melompati blokir autoplay browser secara aman.
5. **Navigasi Mulus (Smooth Scroll)**:
   - Menyuntikkan properti CSS `html { scroll-behavior: smooth; }` secara dinamis pada head tema.

---

## 3. Berkas yang Terpengaruh (Affected Files)

Berikut adalah daftar berkas yang akan dibuat atau dimodifikasi selama Phase 7:

* **[NEW]** [ThemePreviewService.php](file:///Users/ayur/Documents/Werkz/teman.seakad/app/Services/ThemePreviewService.php) — Provider data dummy pratinjau tema.
* **[MODIFY]** [ThemeController.php](file:///Users/ayur/Documents/Werkz/teman.seakad/app/Http/Controllers/Admin/ThemeController.php) — Refaktorisasi metode pratinjau.
* **[MODIFY]** `resources/views/themes/{theme-folder}/index.blade.php` — Penambahan Loading screen, scroll lock, smooth scroll, dan script deferring.
* **[MODIFY]** `resources/views/themes/{theme-folder}/components/music.blade.php` — Penambahan memori state musik dan adaptasi autoplay yang handal.
* **[MODIFY]** `docs/THEME_GUIDE.md` — Pembaruan dokumentasi integrasi pratinjau.
* **[MODIFY]** `docs/DESIGN.md` — Penambahan visual & experience guidelines.
