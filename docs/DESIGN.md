# Wedding Invitation Digital - Design System

Version: 1.0

---

# 1. Design Vision

## Product Identity

Wedding Invitation Digital bukan hanya halaman web, tetapi sebuah **digital wedding experience**.

User experience yang ingin dicapai:

> "Ketika tamu membuka undangan, mereka merasa seperti membuka undangan pernikahan premium."

Design harus memberikan kesan:

* Elegant
* Romantic
* Emotional
* Luxury
* Cinematic
* Personal

Hindari tampilan:

* Website company profile
* Dashboard style
* Template HTML biasa
* Terlalu banyak card/grid

---

# 2. Core Design Principles

## 2.1 Mobile First

Mayoritas tamu membuka melalui smartphone.

Prioritas:

* Mobile screen
* Smooth scrolling
* Touch interaction
* Fast loading

Target:

* 360px - 430px width support
* Responsive tablet
* Desktop enhancement

---

## 2.2 Storytelling Experience

Undangan harus memiliki alur emosional.

Flow:

```
Opening Experience

        ↓

Bride & Groom Introduction

        ↓

Love Story

        ↓

Wedding Event

        ↓

Gallery

        ↓

RSVP

        ↓

Guest Wishes

        ↓

Closing
```

Setiap section harus terasa seperti bagian cerita.

---

# 3. Visual Direction

Default Theme:

Premium Cinematic Wedding

Mood:

```
Luxury
Soft
Elegant
Emotional
Warm
```

Reference feeling:

* Luxury wedding invitation card
* Cinematic wedding film
* Romantic editorial magazine

---

# 4. Typography System

## Heading Font

Gunakan serif elegant.

Contoh:

* Playfair Display
* Cormorant Garamond
* Cinzel

Untuk:

* Nama pasangan
* Judul section
* Quote

## Body Font

Gunakan font readable.

Contoh:

* Inter
* Lora
* Montserrat

Rules:

Heading:

```
Large
Elegant
Letter spacing
```

Body:

```
Clean
Comfortable
Easy reading
```

---

# 5. Color System

## Primary

Elegant neutral:

```
Rose Gold
#B76E79
```

## Background

Soft:

```
Cream
#FFF8F0
```

## Accent

Luxury:

```
Gold
#D4AF37
```

## Text

Primary:

```
#2B2B2B
```

Secondary:

```
#777777
```

---

# 6. Layout Rules

## Full Screen Section

Setiap section utama:

```
min-height: 100vh
```

Gunakan:

* whitespace
* breathing room
* visual balance

---

# 7. Hero / Opening Section

Hero adalah bagian paling penting.

Requirement:

* Full screen
* Background photo/video
* Overlay
* Animation
* Music control
* Opening button

Structure:

```
Background

      ↓

Overlay

      ↓

Wedding Title

      ↓

Bride

      &

Groom

      ↓

Date

      ↓

Open Invitation Button
```

Animation:

Sequence:

```
Background fade

↓

Title appear

↓

Names reveal

↓

Button animation
```

---

# 8. Envelope Opening Experience

Optional premium interaction.

Flow:

```
Closed Invitation

        ↓

User Click

        ↓

Envelope Open Animation

        ↓

Reveal Wedding Page
```

---

# 9. Couple Section

Avoid:

```
Photo
Name
Description
```

Too generic.

Use:

```
Bride


Photo


Name

Short Story


♡

Groom


Photo


Name

Short Story
```

---

# 10. Love Story Section

Format:

Timeline.

Example:

```
2019

First Meet


2022

Relationship


2026

Wedding
```

Use:

* Scroll animation
* Timeline connector
* Photo reveal

---

# 11. Event Section

Must contain:

* Date
* Time
* Venue
* Map
* Countdown

Design:

Avoid table style.

Use:

```
EVENT

12 December 2026


Ceremony

10:00 AM


Reception

12:00 PM
```

---

# 12. Gallery

Avoid:

```
Grid 3x3
```

Use:

* Masonry
* Slider
* Fullscreen viewer

Interaction:

Tap photo:

```
Zoom
Swipe
Close
```

---

# 13. Background Music

Music is part of experience.

Requirement:

Floating music button.

Style:

```
Circular button

Animated rotation

Song information
```

---

# 14. Animation System

Animation should support emotion.

Do:

* Fade
* Slide
* Zoom
* Parallax

Avoid:

* Excessive animation
* Distracting movement

Recommended:

GSAP / AOS

---

# 15. Component Architecture

Each theme wajib memiliki:

```
themes/

premium-cinematic/


components/

Hero

Couple

Story

Event

Gallery

Countdown

Music

RSVP

GuestWish

Footer

```

---

# 16. Theme Structure

Setiap theme harus mempunyai:

```
theme/

metadata

colors

fonts

components

assets

animations
```

Example:

```
premium-cinematic

theme.json

views/

assets/

```

---

# 17. Image Guidelines

Image:

* High resolution
* Optimized
* Aspect ratio consistent

Hero:

```
16:9
or
Full portrait
```

Gallery:

```
4:5
1:1
```

---

# 18. Performance Rules

Required:

* Lazy loading image
* Compress assets
* Avoid huge video
* Optimize animation

Target:

Fast first load.

---

# 19. Premium Feature Guidelines

Optional:

## Guest Personalization

Example:

```
Dear Ayu Rakhmadani
```

## Digital Envelope

## Background Music

## Live Countdown

## RSVP

## Guest Messages

## Gift Section

---

# 20. Design Quality Checklist

Before publishing theme:

Visual:

[ ] Premium feeling

[ ] Consistent typography

[ ] Good spacing

[ ] Mobile perfect

Experience:

[ ] Opening animation

[ ] Smooth scrolling

[ ] Music works

[ ] Gallery works

Technical:

[ ] Fast loading

[ ] Responsive

[ ] SEO friendly

---

# Final Rule

Every wedding invitation theme must feel like:

"A luxury digital wedding card, not a normal website."
