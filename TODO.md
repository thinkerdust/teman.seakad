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

PRODUCTION REQUIREMENT

Implementasikan:

* Migration
* Model
* Controller
* Form Request Validation
* API Resource
* Service Layer
* Repository Pattern
* Seeder
* Factory
* Clean Code
* Responsive Mobile First
* SEO Meta
* Open Graph
* Lazy Load Image
* Storage Management

==================================================

KERJAKAN BERTAHAP

Mulai dari:

PHASE 1:

1. Integrasi TailAdmin

2. Buat Admin Layout

3. Buat Authentication Manual

4. Buat Dashboard

Jangan membuat semua modul sekaligus.

Pastikan setiap phase selesai dan berjalan sebelum lanjut ke phase berikutnya.
