Buatkan aplikasi web Undangan Pernikahan Digital menggunakan stack berikut:

==================================================

TECH STACK

Backend:

* Laravel 11
* PHP 8.2+
* MySQL
* Laravel Native Authentication (tanpa Breeze / Jetstream / Fortify)
* Laravel Sanctum untuk API
* Laravel Vite

Admin Panel:

Gunakan:

* TailAdmin
* Tailwind CSS
* Laravel Blade
* Vue Component jika diperlukan

Frontend Invitation:

Gunakan:

* Vue 3
* Vite
* Tailwind CSS
* Axios
* GSAP Animation

Deployment:

Target:

* Shared Hosting
* Apache
* PHP 8.2+
* MySQL

---

# APPLICATION FLOW

## Business Flow

Landing Page
|
|
Customer Order
|
|
WhatsApp Admin
|
|
Admin Follow Up
|
|
Order Confirmed
|
|
Admin Create User Account
|
|
Assign Invitation Quota
|
|
User Login Dashboard
|
|
Create Invitation
|
|
Publish Invitation

---

==================================================

ARSITEKTUR APLIKASI

Laravel bertanggung jawab untuk:

* Authentication
* Admin Panel
* Database
* API
* File Management
* Invitation Routing

Vue bertanggung jawab untuk:

* Landing Page Undangan
* Template Theme
* Animation
* Interactive Component

Struktur:

Laravel Admin

Blade
+
TailAdmin

Invitation Engine

Vue 3
+
Vite
+
Dynamic Template

==================================================

PHASE 1
SETUP ADMIN PANEL

Implementasikan TailAdmin sebagai dashboard utama.

Struktur:

resources/

├── views

│
├── admin

│   ├── layouts

│   │    ├── app.blade.php

│   │    ├── sidebar.blade.php

│   │    └── header.blade.php

│
│   ├── dashboard.blade.php

│
│   └── users

resources/js/

├── admin

│   ├── app.js

│   └── components

Gunakan TailAdmin layout:

* Sidebar
* Header
* Breadcrumb
* Card
* Table
* Modal
* Form Component

==================================================

PHASE 2
AUTHENTICATION

Jangan gunakan:

* Laravel Breeze
* Jetstream
* Fortify

Buat authentication manual menggunakan Laravel.

Fitur:

* Login
* Logout
* Remember me
* Forgot password
* Reset password

Gunakan:

Auth facade Laravel

Contoh:

Auth::attempt()

Buat:

AuthController

Method:

login()

logout()

forgotPassword()

resetPassword()

Gunakan middleware:

auth

==================================================

PHASE 3
USER MANAGEMENT

Buat modul User Management.

Database:

users

id

name

email

password

phone

avatar

status

last_login_at

created_at

Fitur:

* List User
* Search
* Filter
* Create
* Update
* Delete
* Reset Password

Gunakan TailAdmin:

* Data table
* Modal form
* Validation

==================================================

PHASE 4
ROLE & PERMISSION

Buat RBAC System.

Database:

roles

id

name

description

permissions

id

name

key

role_permissions

role_id

permission_id

user_roles

user_id

role_id

Contoh permission:

dashboard.view

user.view

user.create

user.update

user.delete

theme.view

theme.create

invitation.create

Buat:

PermissionMiddleware

Contoh:

Route::middleware([
'permission:user.create'
])

==================================================

PHASE 5
DYNAMIC MENU MANAGEMENT

Sidebar TailAdmin harus membaca menu dari database.

Database:

menus

id

parent_id

title

icon

route

permission

order

status

Fitur:

Admin dapat:

* Tambah menu
* Edit menu
* Hapus menu
* Atur parent menu
* Atur urutan
* Enable/Disable menu

Menu tampil sesuai permission user.

==================================================

PHASE 6
DASHBOARD

Buat dashboard TailAdmin.

Widget:

* Total User
* Total Invitation
* Total Theme
* Total Guest

Chart:

* Invitation dibuat per bulan
* Visitor statistik

==================================================

PHASE 7
THEME MANAGEMENT

Buat modul Tema Undangan.

Database:

themes

id

name

slug

thumbnail

description

folder

status

Contoh:

floral-elegant

luxury-gold

islamic-wedding

Theme disimpan modular:

resources/js/invitation/templates/

floral-elegant/

```
App.vue

style.css

assets/
```

luxury-gold/

```
App.vue

style.css
```

Setiap theme menerima data:

{

groom_name,

bride_name,

event_date,

venue,

gallery,

music,

story

}

==================================================

PHASE 8
INVITATION MANAGEMENT

Database:

invitations

id

user_id

theme_id

slug

title

status

expired_at

Field:

groom_name

bride_name

akad_date

reception_date

venue

address

maps_url

description

Status:

draft

published

expired

Fitur:

* Create Invitation
* Pilih Theme
* Preview
* Publish
* Disable

==================================================

PHASE 9
PUBLIC INVITATION LINK

URL:

domain.com/{slug}

Contoh:

domain.com/ayu-raka

Flow:

User membuka link:

Laravel:

* Cari invitation berdasarkan slug
* Load theme
* Load data
* Render Vue Invitation

==================================================

PHASE 10
GUEST MANAGEMENT

Database:

guests

id

invitation_id

name

phone

attendance

message

created_at

Fitur:

* Tambah tamu
* Import Excel
* Export
* RSVP

Personal guest link:

domain.com/ayu-raka?to=Budi

Landing:

Dear Budi

==================================================

PHASE 11
INVITATION CONTENT

Tambahkan:

Gallery:

gallery

id

invitation_id

image

sort

Story:

stories

id

invitation_id

title

description

date

Event:

events

id

invitation_id

name

date

time

location

Music:

music

id

invitation_id

file

==================================================

PHASE 12
API

Endpoint:

GET

/api/invitation/{slug}

Response:

{

theme:"floral-elegant",

data:{},

gallery:[],

events:[],

music:""

}

==================================================

PHASE 13
VUE INVITATION ENGINE

Structure:

resources/js/invitation/

main.js

App.vue

components/

Hero.vue

Countdown.vue

Gallery.vue

Story.vue

Event.vue

RSVP.vue

templates/

floral/

luxury/

islamic/

Gunakan dynamic component berdasarkan theme.

==================================================

# PHASE 14

# ORDER MANAGEMENT SYSTEM

## Landing Page Order CTA

Update landing page CTA:

Button:

"Pesan Undangan"

Action:

Redirect WhatsApp

Format:

https://wa.me/{admin_number}?text={message}

Generate automatic message:

Example:

Halo Admin,

Saya tertarik menggunakan layanan Undangan Pernikahan Digital.

Nama:
Tanggal Pernikahan:

Terima kasih.

---

# PHASE 15

# CUSTOMER ORDER MANAGEMENT UPDATE

## Order Lifecycle

Flow:

Customer Order

```
    |
```

Admin Follow Up

```
    |
```

Order Confirmed

```
    |
```

Set Active Period

```
    |
```

Create User Account

```
    |
```

Assign Quota

```
    |
```

User Login

```
    |
```

Create Invitation

---

# ORDER DATABASE UPDATE

Table:

orders

Fields:

id

order_number

customer_name

phone

email

package_id

quota

price

status

start_date

end_date

notes

created_at

updated_at

---

# ORDER STATUS

pending

Customer melakukan order

follow_up

Admin sedang melakukan komunikasi

confirmed

Order disetujui

active

User sudah aktif menggunakan layanan

expired

Masa aktif habis

cancelled

Order dibatalkan

---

# PHASE 16

# PACKAGE MANAGEMENT UPDATE

Table:

packages

Fields:

id

name

description

price

invitation_quota

duration_days

status

Example:

Basic

price:

100000

quota:

1 invitation

duration:

30 hari

Premium

quota:

5 invitation

duration:

365 hari

---

# PHASE 17

# USER ACCOUNT + SUBSCRIPTION

Saat admin confirm order:

System otomatis:

1. Membuat user

users

name

email

password

phone

2. Membuat subscription user

Table:

user_subscriptions

Fields:

id

user_id

order_id

package_id

start_date

end_date

status

created_at

updated_at

---

# USER ACCESS RULE

User dapat login panel hanya jika:

current_date >= start_date

AND

current_date <= end_date

Jika expired:

Redirect:

/subscription-expired

Message:

"Masa aktif akun Anda sudah berakhir, silahkan melakukan perpanjangan"

---

# PHASE 18

# INVITATION ACTIVE PERIOD

Invitation memiliki keterikatan dengan subscription user.

Update table:

invitations

Tambah field:

published_at

expired_at

Rules:

Saat user publish invitation:

expired_at otomatis:

subscription.end_date

Example:

User subscription:

01 Januari 2026

sampai

01 Januari 2027

Invitation:

Published:

05 Januari 2026

Expired:

01 Januari 2027

---

# PUBLIC INVITATION ACCESS RULE

URL:

domain.com/{slug}

Flow:

Guest membuka invitation

System check:

Invitation status:

published

AND

today <= invitation.expired_at

Jika valid:

Render Vue Invitation

Jika expired:

Tampilkan halaman:

"Undangan sudah tidak tersedia"

---

# PHASE 19

# QUOTA + SUBSCRIPTION VALIDATION

Sebelum membuat invitation:

Check:

1. User subscription active

2. Quota tersedia

Logic:

if subscription_expired:

block

if quota_empty:

block

---

# PHASE 20

# MIDDLEWARE

Buat middleware:

SubscriptionMiddleware

Function:

checkActiveSubscription()

Digunakan:

Admin Panel

Example:

Route::middleware([

'auth',

'subscription.active'

])

---

Buat middleware:

InvitationActiveMiddleware

Digunakan:

Public Invitation Route

Example:

Route:

/{slug}

Check:

status = published

expired_at >= now()

---

# PHASE 21

# DASHBOARD UPDATE

Dashboard Admin / Superadmin

Tambahkan widget:

## Subscription Statistic

* Total Active Subscription

* Total Expired Subscription

* Expired This Month

* Upcoming Expired

## Order Statistic

* Total Order

* Pending Order

* Active Order

* Expired Order

## Revenue

* Total Revenue

* Monthly Revenue

Chart:

Subscription Growth

Order Monthly

Revenue Monthly

---

# PHASE 22

# TRANSACTION REPORT UPDATE

Transaction Report menampilkan:

Order Number

Customer

Package

Quota

Start Date

End Date

Status

Filter:

* Date Range

* Package

* Status

* Active / Expired

---

# PHASE 23

# NOTIFICATION SYSTEM

Tambahkan reminder:

Before expired:

H-30

H-7

H-1

Notification:

"Subscription Anda akan berakhir pada tanggal ..."

Channel:

* Dashboard Notification

* Email

* WhatsApp (optional)

---

# PHASE 24

# SERVICE UPDATE

Tambah:

SubscriptionService

Method:

createSubscription()

checkActive()

extendSubscription()

expireSubscription()

QuotaService

checkQuota()

consumeQuota()

InvitationService

publishInvitation()

setExpiredDate()

---

# PHASE 25

# SCHEDULER

Laravel Scheduler:

Daily:

Check subscription expired

Command:

subscriptions:check-expired

Update:

user_subscriptions.status

active -> expired

Invitation:

published -> expired

---

# FINAL BUSINESS RULE

User Account:

Aktif berdasarkan:

Order Period

Invitation:

Aktif berdasarkan:

Subscription Period

Quota:

Berdasarkan:

Package

Order:

Menjadi sumber:

* User creation

* Subscription

* Quota

* Transaction

---

# DEVELOPMENT PRIORITY UPDATE

Next Implementation:

1. Order Management + start/end date

2. Package Management

3. User Subscription

4. Quota System

5. Subscription Middleware

6. Invitation Expiration

7. Transaction Report

8. Dashboard Update

Semua phase wajib selesai dan testing sebelum lanjut.
