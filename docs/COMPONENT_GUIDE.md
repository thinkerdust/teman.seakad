# Wedding Invitation Digital - Component Guide

Version: 1.0

---

# 1. Component Philosophy

Component adalah bagian visual dari undangan.

Setiap component harus:

* memiliki tanggung jawab jelas
* menerima data dari parent
* tidak melakukan query database
* reusable antar theme
* responsive mobile first

Flow:

```
Controller

    ↓

Invitation Data

    ↓

Theme View

    ↓

Components

    ↓

User Experience
```

---

# 2. Standard Component Structure

Semua theme minimal memiliki:

```
components/

hero.blade.php

couple.blade.php

story.blade.php

event.blade.php

countdown.blade.php

gallery.blade.php

music.blade.php

rsvp.blade.php

guest-wish.blade.php

gift.blade.php

footer.blade.php

```

---

# 3. Hero Component

File:

```
components/hero.blade.php
```

## Purpose

First impression experience.

## Required Data

```php
[
    'cover_image',
    'groom_name',
    'bride_name',
    'wedding_date',
    'subtitle'
]
```

Example:

```
The Wedding

Ayu

&

Raka

12 December 2026
```

---

## Layout Rule

Tidak boleh:

```
Image
Text
Button
```

terlihat seperti website.

Gunakan:

```
Full Screen Background


        Couple Name


        Wedding Date


        Open Invitation
```

---

## Animation

Sequence:

```
Background fade


        ↓


Title reveal


        ↓


Names animation


        ↓


Button pulse

```

---

# 4. Couple Component

File:

```
components/couple.blade.php
```

## Purpose

Memperkenalkan pasangan.

## Data:

```php
[
 bride:{
    name,
    photo,
    description
 },

 groom:{
    name,
    photo,
    description
 }
]
```

---

## Layout

Default:

```
BRIDE


Photo

Name


        &


Photo

Name


GROOM
```

---

## Animation

Photo:

* fade up
* zoom

Text:

* delay reveal

---

# 5. Story Component

File:

```
components/story.blade.php
```

## Purpose

Menceritakan perjalanan pasangan.

Data:

```php
[
 {
   year,
   title,
   description,
   image
 }
]
```

Example:

```
2019

First Meet


2022

Relationship


2026

Wedding
```

---

## UI Pattern

Gunakan:

* Timeline
* Scroll animation
* Memory card

---

# 6. Event Component

File:

```
components/event.blade.php
```

## Purpose

Informasi acara.

Data:

```php
[
 ceremony:{
    date,
    time,
    location
 },


 reception:{
    date,
    time,
    location
 }
]
```

---

## Layout

Avoid:

```
table
```

Gunakan:

```
EVENT


12 December 2026


Ceremony

10:00


Reception

12:00

```

---

# 7. Countdown Component

File:

```
components/countdown.blade.php
```

## Purpose

Membangun excitement.

Data:

```
event_date
```

---

## UI

Bukan:

```
12:10:20
```

Gunakan:

```
12

Days


10

Hours


20

Minutes

```

---

# 8. Gallery Component

File:

```
components/gallery.blade.php
```

## Purpose

Menampilkan moment.

Data:

```php
[
 images:[
   url,
   caption
 ]
]
```

---

## Required Feature

Support:

* slider
* fullscreen
* swipe mobile

---

# 9. Music Component

File:

```
components/music.blade.php
```

## Purpose

Background atmosphere.

Data:

```php
[
 title,
 artist,
 url
]
```

---

## UI

Floating button:

```
   🎵


Wedding Song

```

---

# 10. RSVP Component

File:

```
components/rsvp.blade.php
```

## Purpose

Interaksi tamu.

Data:

```
guest_name

attendance

message
```

---

## Feature:

Form:

```
Name


Attend?


Message


Submit

```

---

# 11. Guest Wish Component

File:

```
components/guest-wish.blade.php
```

## Purpose

Ucapan tamu.

Display:

```
Name


Message


Date

```

---

# 12. Gift Component

File:

```
components/gift.blade.php
```

Optional.

Data:

```php
[
 bank_name,
 account_number,
 owner
]
```

---

# 13. Footer Component

File:

```
components/footer.blade.php
```

Closing:

```
Thank You


With Love


Bride & Groom
```

---

# 14. Component Rules

## DO

Gunakan:

```
@include()

@props()

Blade Component

CSS Variables

```

---

## DON'T

Jangan:

```
DB Query

Auth Logic

Business Logic

Hardcode Data

```

---

# 15. Component Example

hero.blade.php

```blade
<section class="hero">


<img src="{{ $cover }}">


<h1>

{{ $bride }}

&

{{ $groom }}

</h1>


<button>

Open Invitation

</button>


</section>
```

---

# 16. Component Styling Rule

Setiap component:

Memiliki:

```
container

content

animation class

theme variable

```

Example:

```html
<section
class="hero"
data-animation="fade-up">

</section>
```

---

# 17. Responsive Rule

Mobile:

```
Single column
Large text
Touch friendly
```

Desktop:

```
More spacing
More visual composition

```

---

# 18. Component Quality Checklist

Before release:

Visual:

[ ] Elegant

[ ] Balanced spacing

[ ] Mobile perfect

Interaction:

[ ] Animation smooth

[ ] No broken section

Technical:

[ ] Reusable

[ ] No database logic

[ ] Theme compatible

---

# 19. Phase 6 - Component Visual Upgrade Regulations

Selama proses visual upgrade di Phase 6, aturan penulisan style dan visual pada component diperketat untuk memastikan kualitas premium yang konsisten:

1. **JANGAN Gunakan Hardcoded Color & Font:**
   - Semua warna wajib menggunakan token dinamis: `var(--theme-primary)`, `var(--theme-secondary)`, `var(--theme-accent)`, `var(--theme-text)`, dan `var(--theme-surface)`.
   - Huruf wajib menggunakan kelas `.font-accent` / `.font-heading` atau style `font-family: var(--theme-font-heading);` / `var(--theme-font-body);`.

2. **Glow & Glassmorphism:**
   - Floating components (seperti Music Button) wajib menggunakan efek glow halus berbasis CSS variable: `box-shadow: 0 0 15px var(--theme-primary);` dan ring pulsing menggunakan standard utility.
   - Gunakan backdrop blur `backdrop-blur-md bg-[var(--theme-surface)]/60 border border-[var(--theme-secondary)]/40 shadow-md` untuk frame premium/semi-transparan (seperti pada Countdown Card).

3. **Konsistensi Border-Radius (Theme-Driven):**
   - Setiap tema memiliki identitas kelengkungan (border-radius) tersendiri yang harus konsisten di seluruh komponen:
     - `floral-elegant`: Standard rounded (`rounded-xl` / `rounded-2xl`).
     - `luxury-gold`: Geometris kaku/persegi (`style="border-radius: 4px;"` atau `2px`).
     - `islamic-wedding`: Signature kubah / daun (`style="border-radius: 1rem 0 1rem 0;"` atau `0.5rem 0 0.5rem 0`).
     - `rustic-forest`: Natural rounded (`rounded-xl` / `rounded-3xl`).
     - `premium-cinematic`: Rounded elegant (`rounded-2xl`).

4. **Keterbatasan Tinggi & Scrollbar Kontainer:**
   - Untuk data dinamis panjang (seperti ucapan tamu pada `guest-wish.blade.php`), batasi tinggi maksimal (`max-h-[400px]`) dan tambahkan custom scrollbar (`custom-scrollbar`) agar layout tidak rusak oleh konten yang banyak.

5. **Integrasi Animasi & Efek Transisi:**
   - Gunakan pemicu GSAP berbasis data atribut: `data-gsap="fade-up"` atau pemicu sejenisnya yang terintegrasi dengan Animation Engine.
   - Gunakan transisi bawaan Alpine.js (`x-transition`) untuk modal lightbox galeri demi kelancaran interaksi user.

---

# Final Rule

Component bukan sekedar potongan HTML.

Component adalah bagian dari pengalaman emosional sebuah pernikahan digital.

```
```
