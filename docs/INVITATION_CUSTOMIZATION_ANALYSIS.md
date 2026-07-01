# Analisis Kustomisasi Tema & Personalisasi Pengguna (Phase 8)

Analisis mendalam mengenai alur data undangan saat ini (*current data flow*), kebutuhan kustomisasi yang diajukan (*required customization*), dan perubahan skema database yang dibutuhkan untuk memfasilitasi personalisasi pengguna tanpa merubah kode program.

---

## 1. Alur Data Saat Ini (Current Data Flow)

Saat ini, data undangan digital mengalir melalui rute publik `/ {slug}` yang ditangani oleh `PublicInvitationController@show` dan rute pratinjau `/theme-preview/{slug}` yang ditangani oleh `ThemeController@preview`.

### Kondisi Saat Ini:
1. **Core Data**: Data inti (Nama Mempelai, Tanggal Akad/Resepsi, Lokasi, Peta) diambil dari kolom-kolom tabel `invitations`.
2. **Relasi Data**:
   * Galeri Foto diambil dari tabel `galleries` (berisi path gambar dan pengurutan).
   * Cerita Cinta (*Love Story*) diambil dari tabel `stories` (berisi judul, deskripsi, tanggal, pengurutan).
   * Acara Tambahan (*Events*) diambil dari tabel `events` (berisi nama acara, tanggal, waktu, lokasi).
   * Musik Latar diambil dari pivot `invitation_music` yang mengarah ke tabel `music` (master data atau custom upload).
3. **Keterbatasan Kustomisasi**:
   * Tidak ada media penyimpanan untuk nama panggilan mempelai (*nicknames*).
   * Tidak ada foto profil mempelai (*groom/bride photos*).
   * Cerita cinta (`stories`) tidak mendukung unggah gambar.
   * Galeri foto (`galleries`) tidak memiliki status visibilitas (`is_visible`), sehingga untuk menyembunyikan foto harus dilakukan penghapusan permanen.
   * Pengguna tidak dapat merubah gaya visual (warna utama, ukuran font, tekstur latar belakang) secara dinamis dari panel admin.

---

## 2. Kebutuhan Kustomisasi & Personalisasi (Required Customization)

Untuk menaikkan tingkat kedalaman fungsionalitas aplikasi menjadi platform undangan pernikahan digital yang kaya fitur (*rich personalizable platform*), beberapa kustomisasi berikut akan ditambahkan:

### A. Informasi Mempelai (Couple Information)
* Menambahkan kolom nama panggilan (*bride_nickname* & *groom_nickname*).
* Menambahkan kolom foto profil mempelai (*bride_photo* & *groom_photo*).
* Pengguna dapat mengunggah foto mempelai terpisah dari galeri umum.

### B. Cerita Cinta (Story)
* Menambahkan kemampuan mengunggah foto/ilustrasi pada setiap milestone cerita cinta dengan menyematkan kolom `image` pada tabel `stories`.

### C. Galeri Foto (Gallery)
* Menambahkan opsi sembunyikan/tampilkan foto tanpa menghapusnya (`is_visible` boolean pada tabel `galleries`).

### D. Gaya Kustom (Custom Style)
* Menambahkan kolom `customization` berformat JSON pada tabel `invitations` untuk menyimpan:
  * `custom_style.primary_color`: Kode hex warna utama (contoh: `#b86b70`).
  * `custom_style.secondary_color`: Kode hex warna sekunder (contoh: `#ebdcd7`).
  * `custom_style.font_scale`: Faktor skala ukuran font (contoh: `1.0`, `1.1`, `0.9`).
  * `custom_style.background_option`: Opsi latar belakang (contoh: `'texture'` atau `'plain'`).

---

## 3. Perubahan Skema Database (Affected Tables)

Sebuah berkas migrasi baru `2026_06_26_145000_add_customization_fields_to_tables.php` akan dibuat untuk memperbarui tabel-tabel berikut:

### 1. Tabel `invitations`
* **[NEW]** `bride_nickname` (string, nullable) — Nama panggilan mempelai wanita.
* **[NEW]** `groom_nickname` (string, nullable) — Nama panggilan mempelai pria.
* **[NEW]** `bride_photo` (string, nullable) — Path foto mempelai wanita.
* **[NEW]** `groom_photo` (string, nullable) — Path foto mempelai pria.
* **[NEW]** `customization` (json, nullable) — Konfigurasi style kustom pengguna.

### 2. Tabel `stories`
* **[NEW]** `image` (string, nullable) — Path foto ilustrasi cerita.

### 3. Tabel `galleries`
* **[NEW]** `is_visible` (boolean, default: true) — Menandakan apakah foto ditampilkan di tema.
