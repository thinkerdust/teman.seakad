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
