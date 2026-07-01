# Wedding Invitation Digital - Theme Guide

Version: 1.0

---

# 1. Theme Concept

Theme adalah sebuah paket desain undangan digital yang terdiri dari:

* Visual style
* Layout
* Component
* Asset
* Animation
* Configuration

Theme bukan hanya warna berbeda.

Setiap theme harus memiliki:

* karakter
* mood
* typography
* experience

Example:

```
Premium Cinematic

Feeling:
Luxury
Elegant
Emotional


Floral Romantic

Feeling:
Soft
Warm
Beautiful
```

---

# 2. Theme Architecture

Structure:

```
resources/

views/

themes/


premium-cinematic/


    index.blade.php


    components/

        hero.blade.php

        couple.blade.php

        story.blade.php

        event.blade.php

        gallery.blade.php

        countdown.blade.php

        music.blade.php

        rsvp.blade.php

        guest-wish.blade.php


    assets/

        css/

        js/

        images/

```

---

# 3. Theme Rendering Flow

Request:

```
/invitation/{slug}
```

Controller:

```
InvitationController
```

Process:

```
Find Invitation


        ↓


Get Active Order


        ↓


Check Theme


        ↓


Load Theme View


        ↓


Render Invitation

```

Example:

Database:

```
theme:

premium-cinematic
```

Render:

```
themes.premium-cinematic.index
```

---

# 4. Theme Database Design

Table:

```
themes
```

Fields:

```
id

name

slug

description

thumbnail

view_path

config

status

created_at

updated_at
```

Example:

```
name:

Premium Cinematic


slug:

premium-cinematic


view_path:

themes.premium-cinematic.index

```

---

# 5. Theme Configuration

Each theme memiliki config.

File:

```
themes/premium-cinematic/theme.json
```

Example:

```json
{
    "name": "Premium Cinematic",

    "version": "1.0",

    "author": "Wedding Digital",

    "colors": {

        "primary": "#B76E79",

        "background": "#FFF8F0",

        "accent": "#D4AF37"

    },


    "fonts": {

        "heading": "Playfair Display",

        "body": "Inter"

    },


    "features": {

        "music": true,

        "countdown": true,

        "gallery": true,

        "rsvp": true

    }

}
```

---

# 6. Component Standard

Semua theme harus memiliki component berikut:

## Hero

File:

```
components/hero.blade.php
```

Responsibility:

* Cover image
* Bride groom name
* Wedding date
* Opening animation

---

## Couple

Menampilkan:

```
Bride


Photo


Name


Groom


Photo


Name
```

---

## Story

Menampilkan:

* Relationship timeline
* Love story
* Memories

---

## Event

Menampilkan:

* Akad
* Reception
* Venue
* Map

---

## Gallery

Menampilkan:

* Photos
* Slider
* Lightbox

---

## Countdown

Menampilkan:

```
Days

Hours

Minutes

Seconds
```

---

## Music

Menampilkan:

* Background music
* Play pause
* Floating button

---

## RSVP

Menampilkan:

* Attendance confirmation
* Guest name
* Message

---

# 7. Data Contract

Theme tidak boleh langsung query database.

Bad:

```php
DB::table('users')->get()
```

Good:

Controller memberikan data:

```php
return view(
'themes.premium.index',
[
 'invitation'=>$invitation,
 'gallery'=>$gallery,
 'events'=>$events
]);
```

Theme hanya bertugas:

DISPLAY.

---

# 8. Blade Example

index.blade.php

```blade
@extends('themes.layout')


@section('content')


@include(
'themes.premium.components.hero'
)


@include(
'themes.premium.components.couple'
)


@include(
'themes.premium.components.story'
)


@include(
'themes.premium.components.event'
)


@endsection

```

---

# 9. Theme Asset Management

Asset harus isolated.

Example:

```
public/themes/


premium-cinematic/


css/

style.css


js/

animation.js


images/

```

---

# 10. Theme CSS Rule

Gunakan CSS variable.

Example:

```css
:root {

--primary:#B76E79;

--background:#FFF8F0;

--accent:#D4AF37;

}

```

Jangan hardcode warna di component.

Bad:

```css
color:red;
```

Good:

```css
color:var(--primary);
```

---

# 11. Animation Rule

Setiap theme wajib memiliki:

Opening animation:

```
Hero reveal

Text animation

Button animation

```

Scroll animation:

```
Section reveal

Image animation

Timeline animation

```

---

# 12. Theme Customization

User dapat mengubah:

* Nama mempelai
* Foto
* Warna
* Musik
* Gallery

Tetapi:

Layout tetap milik theme.

---

# 13. Multiple Theme Support

Contoh:

```
themes/


premium-cinematic


floral-romantic


islamic-elegant


minimalist-modern

```

Semua mengikuti contract:

```
Hero

Couple

Story

Event

Gallery

RSVP

```

---

# 14. Theme Development Checklist

Before adding new theme:

Structure:

[ ] Folder lengkap

[ ] Component tersedia

[ ] Asset terpisah

Design:

[ ] Mengikuti DESIGN.md

[ ] Mobile responsive

[ ] Premium feeling

Technical:

[ ] Tidak query database

[ ] Tidak merusak theme lain

[ ] Asset optimized

---

# 15. Golden Rule

Theme adalah sebuah pengalaman visual.

Jangan membuat:

"HTML berbeda dengan warna berbeda"

Tetapi buat:

"Pengalaman pernikahan berbeda dengan karakter berbeda"

```
```

---

# 16. Theme Configuration v2

Versi baru dari berkas `theme.json` memperkenalkan manajemen identitas, desain visual yang terpusat, serta penyiapan sistem transisi gerakan (motion configuration).

## Struktur Skema

```json
{
    "name": "Floral Elegant",
    "version": "2.0",
    "author": "Teman Seakad",
    "identity": {
        "style": "romantic",
        "mood": ["soft", "warm", "beautiful"],
        "description": "Soft and romantic floral wedding experience"
    },
    "design": {
        "colors": {
            "primary": "#b86b70",
            "secondary": "#ebdcd7",
            "accent": "#8fa89b",
            "background": "#faf6f0",
            "text": "#4e3e3b"
        },
        "typography": {
            "heading": "Playfair Display",
            "body": "Instrument Sans"
        }
    },
    "motion": {
        "opening": "fade-in",
        "scroll": "fade-up",
        "gallery": "zoom-in"
    },
    "assets": {
        "hero": {
            "background": "images/hero/main.jpg",
            "overlay": true
        },
        "ornaments": [
            "ornaments/floral-frame.png"
        ],
        "background": {
            "texture": "backgrounds/soft.png"
        },
        "audio": {
            "enabled": true
        }
    },
    "features": {
        "music": true,
        "gallery": true,
        "countdown": true,
        "rsvp": true,
        "stories": true,
        "gift": true
    }
}
```

## Cara Penggunaan (ThemeConfigService)

Untuk menjaga kompatibilitas ke belakang dan merujuk nilai konfigurasi secara aman tanpa pembacaan JSON langsung, gunakan `ThemeConfigService`:

```php
// Di Controller:
$themeConfig = app(ThemeConfigService::class)->load($theme);
return view($view, compact('invitation', 'themeConfig'));

// Di Blade:
{{ $themeConfig['identity']['style'] }}
{{ $themeConfig['design']['colors']['primary'] }}
```

---

# 17. Animation System

Sistem animasi bersifat terpusat dan config-driven, menggantikan penulisan GSAP manual di blade views.

## Motion Config Schema

```json
{
    "motion": {
        "opening": {
            "type": "cinematic-reveal",
            "duration": 3000
        },
        "scroll": {
            "type": "parallax-reveal",
            "duration": 2000
        },
        "gallery": {
            "type": "zoom",
            "transition": "smooth"
        },
        "button": {
            "type": "hover-glow"
        }
    }
}
```

## Global Helper Usage

Di dalam template komponen, sematkan helper `{!! themeAnimation('component') !!}` untuk menyuntikkan data atribut animasi:

```html
<!-- Couple Section -->
<section class="py-16" {!! themeAnimation('couple') !!}>
    ...
</section>
```

## Presets

Sistem mendukung preset animasi yang terletak di `resources/themes/presets/animations/`:
- `cinematic.json`: Transisi lambat, megah, & parallax.
- `soft.json`: Slide halus & stagger fade.
- `minimal.json`: Fade cepat & bersih.

Tema dapat menggunakan preset ini dengan mendefinisikannya secara implisit lewat `"identity.style"` atau eksplisit lewat `"preset"` di `theme.json`.

---

# 18. Theme Asset System

Sistem Aset Tema mendukung pengelolaan aset visual (seperti cover hero, ornamen dekoratif, tekstur latar belakang) dan audio (musik latar) secara dinamis menggunakan konfigurasi `theme.json` dan helper global.

## Aset Schema di `theme.json`

Setiap tema harus mendefinisikan pemetaan aset di bawah kunci `"assets"`:

```json
{
    "assets": {
        "hero": {
            "background": "images/hero/main.jpg",
            "overlay": true
        },
        "ornaments": [
            "ornaments/floral-frame.png",
            "ornaments/floral-corner.png"
        ],
        "background": {
            "texture": "backgrounds/soft.png"
        },
        "audio": {
            "enabled": true
        }
    }
}
```

Jalur aset di atas bersifat relatif terhadap folder publik tema: `public/themes/{theme_folder}/`.

## Helper Global `themeAsset()`

Di dalam template Blade komponen, gunakan helper `themeAsset(string $key)` untuk memuat berkas aset secara dinamis:

```html
<!-- Mengambil Gambar Latar Hero -->
<div class="hero-bg" style="background-image: url('{{ themeAsset('hero.background') }}')"></div>

<!-- Mengambil Ornamen Pertama (Indeks 0) -->
<img src="{{ themeAsset('ornaments.0') }}" class="ornament-top" />
```

### Mekanisme Fallback

Fungsi `themeAsset()` secara otomatis mendukung pengembalian nilai default jika kunci aset tidak terdefinisi:
* `hero.background`: Mengembalikan `/assets/demo/gallery/IMG_8305.jpg`
* `audio`: Mengembalikan `/assets/demo/music/lagu-nikah.mp3`

---

# 19. Theme Preview System

Untuk memberikan pengalaman pratinjau tema yang realistis dan premium, sistem memanfaatkan komponen interaksi berikut:

## 1. Unified Preview Data Provider (`ThemePreviewService`)
* Logika penyediaan data tiruan (*dummy data*) terpusat pada `App\Services\ThemePreviewService`.
* Semua data mempelai menggunakan nama standardisasi **Ayu & Rakhmadani** tertanggal **10 Oktober 2026** bertempat di **Lembah Hijau Sentul**.
* View Blade tema harus menggunakan data dari `$invitationData` untuk merender informasi acara demi menghindari inkonsistensi data.

## 2. Premium Loading Screen & GSAP Transitions
* Setiap file `index.blade.php` wajib menyertakan kontainer `#loading-screen` berupa double spinner di awal `<body>`.
* Layar loading di-fade out secara halus menggunakan animasi GSAP setelah seluruh aset gambar/audio selesai diunduh:
  ```javascript
  window.addEventListener('load', () => {
      const loader = document.getElementById('loading-screen');
      if (loader && typeof gsap !== 'undefined') {
          gsap.to(loader, {
              opacity: 0,
              duration: 0.8,
              ease: 'power2.out',
              onComplete: () => loader.remove()
          });
      }
  });
  ```

## 3. Scroll Lock & Smooth Scroll
* Elemen `<body>` disetel dengan kelas `overflow-hidden` di awal pemuatan untuk mengunci scroll halaman sebelum tombol "Buka Undangan" diklik.
* Navigasi antar section menggunakan transisi mulus dengan menyuntikkan `html { scroll-behavior: smooth; }` pada tag `<style>`.

## 4. Background Music Autoplay & State Recovery
* Pemutar musik menyimpan status terakhir (`music_playing`) di `localStorage`.
* Jika browser memblokir autoplay setelah halaman dimuat ulang, sistem akan memutar musik latar saat pengguna melakukan interaksi klik pertama kali pada dokumen (`document.addEventListener('click', ...)`).

---

# 20. Theme Customization & Personalization System

Sistem Kustomisasi Tema memfasilitasi pengguna untuk menyesuaikan data mempelai, visual style, galeri foto, cerita cinta, dan musik latar melalui dashboard admin tanpa mengubah layout dasar tema.

## 1. Visual Style Customization (Design Token Overrides)
Pengguna dapat mempersonalisasi gaya visual tema yang akan diubah menjadi variabel CSS dinamis melalui `ThemeTokenService`:
* **Warna Kustom**: `primary_color` dan `secondary_color` (disimpan dalam kode HEX, e.g., `#ff5500`). Jika diisi, warna ini akan mengesampingkan warna bawaan tema.
* **Skala Font**: `font_scale` (angka desimal antara `0.5` dan `2.0`). Mengatur variabel CSS `--theme-font-scale` untuk perbesaran/pengecilan teks.
* **Latar Belakang**: `background_option` (`texture` atau `plain`). Jika diatur ke `plain`, tekstur latar belakang bawaan tema dinonaktifkan (`--theme-background-texture` diset ke `none`).

## 2. Personalization of Couple Details
Setiap komponen Mempelai (`couple.blade.php`) harus mendukung fallbacks dan foto kustom:
* **Nickname Fallback**: Gunakan format `groom_nickname ?? groom_name` dan `bride_nickname ?? bride_name`.
* **Avatar Mempelai**: Gunakan pengondisian `@if(!empty($invitationData['groom_photo']))` untuk menampilkan tag `<img>` berisi URL foto kustom. Jika kosong, tampilkan inisial/singkatan karakter pertama.

## 3. Cerita Cinta & Galeri Foto
* **Foto Milestone Cerita Cinta**: Setiap item cerita cinta (`story.blade.php`) menampilkan gambar kustom dari kunci `image` jika diunggah pengguna.
* **Filter Visibilitas Galeri**: Galeri (`gallery.blade.php`) hanya merender foto dengan status `is_visible => true`. Controller menyaring foto tidak terlihat secara otomatis sebelum dikirim ke view.




