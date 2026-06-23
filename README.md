# Teman Seakad

**Teman Seakad** adalah aplikasi web manajemen dan mesin undangan pernikahan digital berbasis **Laravel 12** dan **Vue 3**. Aplikasi ini dirancang dengan dua sisi utama: landing page publik / engine undangan (Vue 3) dan panel admin manajemen (Blade + Alpine.js).

Bahasa utama untuk antarmuka pengguna, pesan validasi, dan dokumentasi operasional menggunakan **Bahasa Indonesia**.

---

## Tech Stack

| Layer | Teknologi |
| :--- | :--- |
| **Backend** | PHP 8.2+, Laravel 12 |
| **Frontend** | Vue 3 (Invitation Engine), Blade templates, Alpine.js (Admin Panel) |
| **Styling** | Tailwind CSS 4 (via `@tailwindcss/vite` plugin) |
| **Animation** | GSAP 3 (ScrollTrigger, Page Transition) |
| **Build Tool** | Vite 7 + `laravel-vite-plugin` |
| **Database** | SQLite (Development), MySQL 8.0 (Production / Docker) |
| **Container** | Docker Compose (PHP-FPM + Nginx + MySQL) |
| **Testing** | PHPUnit 11 (In-memory SQLite) |
| **Linting** | Laravel Pint |

---

## Fitur Utama

1. **Panel Admin (Dashboard)**
   - Autentikasi manual berbasis Session.
   - Manajemen Pengguna (CRUD) dengan filter pencarian, status (aktif/nonaktif), avatar, dan pencatatan riwayat login terakhir (`last_login_at`).
   - Proteksi Superadmin bawaan dari penghapusan akun, serta proteksi penghapusan akun sendiri.
2. **Vue Invitation Engine**
   - Halaman undangan digital interaktif dan dinamis menggunakan Vue 3.
   - Pilihan 4 tema premium bawaan:
     - `floral-elegant` (Aestetika floral pastel, warna rose & sage, font serif)
     - `luxury-gold` (Warna latar gelap dengan aksen emas mewah berkilau, font Cinzel)
     - `islamic-wedding` (Aksen hijau zamrud, ornamen geometris islami, Bismillah header, font Amiri)
     - `rustic-forest` (Warna kayu/tanah alami, ornamen dedaunan, garis putus-putus)
   - 5 Komponen Bersama (Shared Components):
     - **Hero Section** (GSAP entrance animation)
     - **Countdown Timer** (Reactive countdown ke hari H)
     - **Love Story Timeline** (Vertical timeline dengan GSAP ScrollTrigger)
     - **Event Details** (Kartu detail akad/resepsi dengan integrasi Google Maps)
     - **Gallery Showcase** (Grid foto modern dengan custom touch-swipe lightbox modal)
   - Sistem musik latar otomatis (Floating Audio player) yang terintegrasi dengan tombol pembuka undangan untuk melewati pembatasan autoplay browser.
3. **Konfirmasi RSVP**
   - Form konfirmasi kehadiran (RSVP) langsung terintegrasi di bagian bawah undangan.
   - Pengiriman data RSVP asinkron via Axios ke endpoint backend.
4. **API Endpoint & Resource**
   - Endpoint `GET /api/invitation/{slug}` untuk mengambil data undangan lengkap beserta galeri, cerita, tema, dan acara terkait dalam format JSON terstruktur.
   - Dilengkapi penanganan status (Draft / Expired) yang aman.

---

## Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Controller untuk panel admin (DashboardController, UserController)
│   │   ├── Api/            # API Controllers (InvitationApiController)
│   │   └── Auth/           # Controller autentikasi (AuthController)
│   └── Resources/          # API JSON Resources (InvitationResource)
├── Models/                 # Eloquent Models (User, Invitation, Event, Gallery, Story, RSVP, Theme)
└── Providers/              # Service Providers

resources/
├── css/
│   ├── app.css             # CSS entry point untuk halaman undangan
│   ├── admin.css           # CSS entry point untuk panel admin
│   └── admin/              # CSS per-modul admin
├── js/
│   ├── app.js              # JS entry point untuk halaman undangan (Vue 3 mounting + router)
│   ├── bootstrap.js        # Axios setup
│   └── invitation/
│       ├── components/     # Komponen Vue bersama (Hero, Countdown, Gallery, dll)
│       └── templates/      # 4 Direktori tema undangan (floral-elegant, luxury-gold, dll)
└── views/
    ├── admin/              # Blade views panel admin
    ├── auth/               # Blade views halaman login & auth
    └── public/             # Blade views halaman publik (invitation.blade.php)

routes/
├── web.php                 # Web routes (admin panel, auth, public pages)
└── api.php                 # API routes (/api/invitation/{slug})
```

---

## Instalasi & Setup Awal

Ikuti langkah-langkah di bawah ini untuk menjalankan project di lingkungan lokal:

1. **Clone repository dan masuk ke folder project:**
   ```bash
   git clone <repository-url>
   cd teman.seakad
   ```

2. **Jalankan script setup komprehensif:**
   Script ini akan menyalin berkas `.env`, memasang dependensi PHP & Node, menghasilkan app key, menjalankan migrasi database, dan melakukan seeding data awal.
   ```bash
   composer setup
   ```

3. **Jalankan Server Pengembangan (Dev Server):**
   Gunakan perintah concurrent untuk menjalankan server artisan, antrean queue, log pail, dan Vite sekaligus:
   ```bash
   composer dev
   ```

4. **Akses Aplikasi:**
   Buka peramban (browser) Anda dan akses alamat:
   - Landing Page / Undangan: `http://127.0.0.1:8000/{slug}`
   - Panel Admin: `http://127.0.0.1:8000/admin` (Gunakan kredensial admin default)

---

## Pengujian (Testing)

Aplikasi ini dilengkapi pengujian fitur terotomatisasi menggunakan PHPUnit. 
Untuk menjalankan semua unit & feature test:

```bash
composer test
# atau
php artisan test
```

---

## Perintah Lanjutan (Docker & Linting)

- **Menjalankan environment Docker (MySQL & Nginx):**
  ```bash
  docker compose up -d
  docker compose exec app php artisan migrate
  docker compose exec app php artisan db:seed
  ```
- **Linting & Code Formatting PHP:**
  ```bash
  ./vendor/bin/pint
  ```

---

## Aturan Pengembangan Penting

1. **Bahasa**: Tulis komentar kode dan pesan validasi dalam **Bahasa Indonesia**. Nama kelas, metode, variabel, dan tabel tetap dalam **Bahasa Inggris**.
2. **Form Request**: Selalu pisahkan kelas validasi (misal: `StoreUserRequest` & `UpdateUserRequest`) alih-alih melakukan validasi manual di dalam controller.
3. **Aesthetics & UI**: Pastikan antarmuka undangan selalu rapi, responsif (mobile-first), dan modern menggunakan utility classes dari Tailwind CSS 4.
4. **Alpine.js & Vue**: Gunakan Alpine.js untuk fitur interaktif di area Admin Panel. Gunakan Vue 3 khusus untuk Invitation Engine / area publik. Jangan campur kedua library tersebut dalam satu halaman.
