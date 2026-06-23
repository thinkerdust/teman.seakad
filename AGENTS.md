# AGENTS.md — Teman Seakad

Panduan untuk AI coding agent yang bekerja pada codebase ini.

## Deskripsi Project

**Teman Seakad** adalah aplikasi web akademik berbasis Laravel 12 dengan admin panel untuk mengelola data pengguna. Project ini memiliki dua sisi: landing page publik (Vue.js — dalam rencana) dan admin dashboard (Blade + Alpine.js). Bahasa utama antarmuka dan pesan validasi menggunakan **Bahasa Indonesia**.

## Tech Stack

| Layer         | Teknologi                                                    |
| ------------- | ------------------------------------------------------------ |
| Backend       | PHP 8.2+, Laravel 12                                         |
| Frontend      | Blade templates, Alpine.js + @alpinejs/persist, Vue 3 (rencana) |
| Styling       | Tailwind CSS 4 (via `@tailwindcss/vite` plugin)              |
| Animation     | GSAP 3                                                       |
| Build Tool    | Vite 7 + `laravel-vite-plugin`                               |
| Database      | SQLite (development), MySQL 8.0 (Docker/production)          |
| Container     | Docker Compose (PHP-FPM + Nginx + MySQL)                     |
| Testing       | PHPUnit 11 (in-memory SQLite)                                |
| Linting       | Laravel Pint                                                 |

## Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Controller untuk admin panel (DashboardController, UserController)
│   │   └── Auth/           # Controller autentikasi (AuthController)
│   └── Requests/
│       ├── Admin/          # Form Request validation untuk admin (StoreUserRequest, UpdateUserRequest)
│       └── Auth/           # Form Request validation untuk auth (LoginRequest, ForgotPasswordRequest, ResetPasswordRequest)
├── Mail/                   # Mailable classes (ResetPasswordMail)
├── Models/                 # Eloquent models (User)
└── Providers/              # Service providers (AppServiceProvider)

resources/
├── css/
│   ├── app.css             # CSS entry point untuk landing page publik
│   ├── admin.css           # CSS entry point untuk admin panel (imports admin/*.css)
│   └── admin/              # CSS per-modul admin (users.css)
├── js/
│   ├── app.js              # JS entry point untuk landing page publik
│   ├── bootstrap.js        # Axios setup
│   └── admin/
│       ├── app.js          # JS entry point admin (Alpine.js init + plugin registration)
│       └── users.js        # Alpine.js components untuk manajemen user
└── views/
    ├── admin/
    │   ├── layouts/        # Layout admin (app.blade.php, header.blade.php, sidebar.blade.php)
    │   ├── users/          # Views manajemen user (index.blade.php)
    │   └── dashboard.blade.php
    ├── auth/               # Views autentikasi (login, forgot-password, reset-password)
    ├── components/admin/   # Blade components (alert, breadcrumb, card, stat-card)
    ├── emails/             # Email templates (reset-password)
    └── welcome.blade.php   # Landing page publik

routes/
├── web.php                 # Semua route definisi (public, guest auth, admin)
└── console.php             # Artisan commands

database/
├── migrations/             # Schema migrations
├── seeders/                # AdminSeeder, DatabaseSeeder
└── database.sqlite         # SQLite database (development)

docker/
├── nginx/                  # Nginx config
└── php/                    # PHP Dockerfile
```

## Konvensi & Pola Kode

### Bahasa

- **Komentar dan pesan validasi** ditulis dalam **Bahasa Indonesia**.
- **Nama class, method, variabel, dan route** menggunakan **Bahasa Inggris** sesuai konvensi Laravel.
- Contoh docblock: `/** Tampilkan daftar user (dengan search & filter). */`
- Contoh validation message: `'name.required' => 'Nama lengkap wajib diisi.'`

### Controller

- Satu controller per domain/resource, dikelompokkan dalam subdirektori namespace (`Admin\`, `Auth\`).
- Gunakan method standar: `index`, `store`, `update`, `destroy` untuk CRUD.
- Method khusus seperti `resetPassword` boleh ditambahkan jika diperlukan.
- Selalu gunakan **Form Request** terpisah untuk validasi (`StoreUserRequest`, `UpdateUserRequest`), jangan validate langsung di controller kecuali untuk kasus sangat sederhana.
- Return redirect dengan flash message setelah operasi mutasi: `->with('success', '...')` atau `->with('error', '...')`.

### Model

- Gunakan `$fillable` untuk mass assignment protection (bukan `$guarded`).
- Definisikan `casts()` sebagai method (bukan property) sesuai Laravel 12.
- Trait standar: `HasFactory`, `Notifiable`.

### Form Request

- Setiap Form Request meng-override `authorize()`, `rules()`, dan `messages()`.
- Pesan validasi custom ditulis dalam Bahasa Indonesia.
- Pisahkan `StoreXxxRequest` dan `UpdateXxxRequest` untuk operasi create vs update.

### Routing

- Route dikelompokkan dengan middleware (`guest`, `auth`) dan prefix (`admin`).
- Gunakan named routes dengan format `admin.{resource}.{action}` (contoh: `admin.users.index`).
- Semua route admin berada di bawah prefix `/admin`.

### Views & Frontend

- **Admin panel** menggunakan Blade layout inheritance (`admin.layouts.app` sebagai master layout).
- Layout terdiri dari: `app.blade.php` (shell), `sidebar.blade.php`, `header.blade.php`.
- **Reusable components** dibuat sebagai Blade components di `views/components/admin/` (alert, breadcrumb, card, stat-card).
- Interaktivitas admin menggunakan **Alpine.js** (bukan Vue). Vue direncanakan untuk landing page publik.
- Alpine.js di-init di `resources/js/admin/app.js`, plugin `persist` terdaftar sebelum `Alpine.start()`.
- Komponen Alpine per-modul dipisah ke file sendiri (contoh: `users.js`).
- **Dark mode** didukung via custom variant: `@custom-variant dark (&:where(.dark, .dark *))`.
- CSS per-modul disimpan di `resources/css/admin/` dan di-import dari `admin.css`.

### Vite Entry Points

Ada dua bundle terpisah yang di-define di `vite.config.js`:
1. `resources/css/app.css` + `resources/js/app.js` → Landing page publik
2. `resources/css/admin.css` + `resources/js/admin/app.js` → Admin panel

Jangan campur import antara kedua bundle ini.

### Styling

- Gunakan **Tailwind CSS 4** dengan syntax `@import 'tailwindcss'`.
- Font admin: `Inter`, font publik: `Instrument Sans`.
- Custom scrollbar styling dan `[x-cloak]` hide sudah di-define di `admin.css`.
- Gunakan Tailwind utility classes di Blade, bukan inline styles.

### Database

- Development default menggunakan **SQLite** (`database/database.sqlite`).
- Docker environment menggunakan **MySQL 8.0** (credentials di `docker-compose.yml`).
- Migration mengikuti konvensi timestamp Laravel: `YYYY_MM_DD_HHMMSS_description.php`.
- User memiliki field tambahan: `phone`, `avatar`, `status` (enum: active/inactive), `last_login_at`.

### File Upload

- Avatar disimpan ke disk `public` via `Storage::disk('public')`, path di-store di kolom `avatar`.
- Format yang diterima: jpeg, png, jpg, gif. Max 2MB.
- Avatar lama dihapus saat update atau delete user.

### Autentikasi

- Auth menggunakan session-based (bukan API token), guard default Laravel.
- Login mencatat `last_login_at` timestamp.
- User dengan status `inactive` tidak bisa login.
- Reset password menggunakan mekanisme token manual (bukan `Password::sendResetLink`), token hash disimpan di tabel `password_reset_tokens`, expired setelah 60 menit.
- Email reset password dikirim via `App\Mail\ResetPasswordMail`.
- Superadmin default (`admin@teman-seakad.com`) dilindungi dari penghapusan.
- User tidak bisa menghapus akun sendiri.

## Perintah Development

```bash
# Setup awal (install deps, generate key, migrate, build assets)
composer setup

# Jalankan dev server (concurrent: artisan serve + queue + pail logs + vite)
composer dev

# Build assets production
npm run build

# Jalankan tests
composer test
# atau
php artisan test

# Lint & format PHP
./vendor/bin/pint

# Docker environment
docker compose up -d
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed

# Seed admin user
php artisan db:seed --class=AdminSeeder
```

## Testing

- PHPUnit 11 dengan config di `phpunit.xml`.
- Test environment menggunakan **SQLite in-memory** (`DB_DATABASE=:memory:`).
- Test suites: `tests/Unit/` dan `tests/Feature/`.
- Jalankan dengan `composer test` atau `php artisan test`.
- Environment overrides di phpunit.xml: cache=array, mail=array, queue=sync, session=array.

## Aturan Penting

1. **Jangan edit `.env`** — gunakan `.env.example` sebagai referensi.
2. **Jangan commit `vendor/` atau `node_modules/`** — sudah ada di `.gitignore`.
3. **Selalu buat migration** untuk perubahan skema database, jangan edit migration yang sudah dijalankan.
4. **Gunakan Form Request** untuk validasi, bukan manual `$request->validate()` di controller (kecuali kasus sangat sederhana).
5. **Flash messages** harus dalam Bahasa Indonesia dan menggunakan key `success` atau `error`.
6. **Jangan hapus komentar atau docstring** yang sudah ada kecuali diminta secara eksplisit.
7. **Alpine.js untuk admin**, Vue untuk landing page publik — jangan campur.
8. **Indent 4 spasi** untuk PHP, Blade, JS. **Indent 2 spasi** untuk YAML. Lihat `.editorconfig`.
