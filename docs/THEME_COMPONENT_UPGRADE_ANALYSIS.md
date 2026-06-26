# Analisis Upgrade Visual Komponen Tema - Phase 6

Dokumen ini berisi hasil audit menyeluruh terhadap komponen-komponen Blade pada kelima tema (*floral-elegant, luxury-gold, premium-cinematic, islamic-wedding, rustic-forest*) serta rencana peningkatan visual agar memenuhi standar premium, terintegrasi dengan mesin animasi, design tokens, dan sistem aset.

---

## 1. Kondisi Komponen Saat Ini & Rencana Peningkatan

### A. Hero (`hero.blade.php`)
* **Kondisi Saat Ini**: Menampilkan teks nama mempelai, sub-judul, nama tamu undangan, dan tombol buka undangan. Background cover sudah menggunakan `themeAsset()` namun posisinya masih bergantung pada CSS statis.
* **Peningkatan**:
  * Integrasikan dengan konfigurasi tata letak `"layout": { "hero": "fullscreen" }` dari `theme.json` dan dukung variasi layout lainnya.
  * Tambahkan render ornamen dekoratif dinamis dari `"assets.ornaments"` (misal ornamen frame atas/bawah/tengah).
  * Bersihkan CSS inline keras dan gunakan murni `var(--theme-*)` untuk pewarnaan serta font.
  * Integrasikan dengan GSAP `cinematic-reveal` atau transisi fade-in terpusat saat tombol "Buka Undangan" diklik.

### B. Couple (`couple.blade.php`)
* **Kondisi Saat Ini**:
  * Tiga tema (*floral-elegant, islamic-wedding, rustic-forest*) mengintegrasikan sub-bagian Hero inline di atas informasi mempelai.
  * Nama mempelai dan sub-judul mempelai masih ditulis dengan CSS keras atau font inline seperti `Great Vibes`.
* **Peningkatan**:
  * Ubah style font inline `Great Vibes` menjadi class `.font-accent` terstandarisasi.
  * Tambahkan ornamen pembatas dekoratif (`ornaments.1` atau `ornaments/wood-divider.png`) dinamis di antara detail mempelai pria dan wanita.
  * Tingkatkan visual foto mempelai (tambahkan bingkai, border bulat, atau bayangan premium).
  * Sematkan efek GSAP stagger reveal untuk masing-masing profil mempelai.

### C. Story (`story.blade.php`)
* **Kondisi Saat Ini**: Menampilkan daftar cerita perjalanan cinta mempelai dalam baris vertikal sederhana.
* **Peningkatan**:
  * Buat tampilan timeline yang premium dengan garis tengah dinamis (`var(--theme-accent)` atau `var(--theme-primary)`).
  * Tambahkan penanda tanggal/tahun (*date marker*) bulat yang menonjol dan kartu memori bercerita (*memory card*) dengan bayangan lembut.
  * Terapkan efek transisi scroll reveal GSAP (fade-up/slide-in bergantian kiri-kanan).

### D. Event (`event.blade.php`)
* **Kondisi Saat Ini**: Menampilkan detail Akad Nikah dan Resepsi menggunakan layout grid atau tumpukan vertikal sederhana.
* **Peningkatan**:
  * Gunakan tata letak kartu mewah (`islamic-card`, `luxury-card`, atau sejenisnya) dengan padding dan radius sudut yang seimbang.
  * Tambahkan ikon perincian (Waktu, Tanggal, Lokasi) yang harmonis dengan warna `var(--theme-primary)`.
  * Tonjolkan tombol navigasi peta ("Lihat Peta" / "Google Maps") agar interaktif dan premium.

### E. Gallery (`gallery.blade.php`)
* **Kondisi Saat Ini**: Menampilkan gambar dalam grid 2 kolom dengan lightbox modal Alpine.js.
* **Peningkatan**:
  * Buat layout masonry/grid estetik dengan rasio aspek gambar yang bervariasi secara harmonis.
  * Terapkan hover overlay yang elegan (misal: blur halus, scale zoom, dan ikon plus mewah).
  * Pastikan modal lightbox Alpine.js memiliki transisi fade/zoom yang mulus.

### F. Countdown (`countdown.blade.php`)
* **Kondisi Saat Ini**: Kotak countdown numerik standar (Hari, Jam, Menit, Detik).
* **Peningkatan**:
  * Buat desain angka yang tebal dengan font heading (`var(--theme-font-heading)`).
  * Berikan border halus atau latar belakang semi-transparan (*glassmorphism*) pada tiap kotak satuan waktu.

### G. Music (`music.blade.php`)
* **Kondisi Saat Ini**: Tombol melayang di pojok kanan bawah dengan ikon play/pause dan transisi spin.
* **Peningkatan**:
  * Perhalus animasi spin-slow dan berikan efek glow pulsasi saat musik berputar.
  * Pastikan interaksi toggle Alpine.js terasa instan dan responsif.

### H. RSVP (`rsvp.blade.php`)
* **Kondisi Saat Ini**: Form konfirmasi kehadiran standar dengan input teks, selectbox kehadiran, dan textarea pesan ucapan.
* **Peningkatan**:
  * Perindah elemen input (focus border berwarna `var(--theme-primary)` dan label yang mengambang).
  * Jadikan desain tombol submit tebal dan premium, lengkap dengan efek hover/active.

### I. Guest Wish (`guest-wish.blade.php`)
* **Kondisi Saat Ini**: Menampilkan daftar pesan ucapan tamu dalam list kartu sederhana.
* **Peningkatan**:
  * Terapkan layout masonry card atau tumpukan vertikal dengan warna latar permukaan lembut (`var(--theme-surface)`).
  * Batasi tinggi area pesan dengan scrollbar kustom agar halaman tidak terlalu panjang secara vertikal.

---

## 2. Berkas yang Terpengaruh (*Affected Files*)

Semua file komponen Blade di kelima folder tema berikut akan diperbarui:

```
resources/views/themes/
├── floral-elegant/components/
├── luxury-gold/components/
├── premium-cinematic/components/
├── islamic-wedding/components/
└── rustic-forest/components/
```

Juga file konfigurasi `theme.json` masing-masing tema untuk menyertakan layout default:
* `resources/views/themes/floral-elegant/theme.json`
* `resources/views/themes/luxury-gold/theme.json`
* `resources/views/themes/premium-cinematic/theme.json`
* `resources/views/themes/islamic-wedding/theme.json`
* `resources/views/themes/rustic-forest/theme.json`

Serta penambahan skema baru ke dalam `ThemeConfigService.php` untuk mendukung properti `layout`.
