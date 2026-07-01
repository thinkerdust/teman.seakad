# Theme Customization & Data Flow Analysis

## Existing Data Flow

1. **Invitation Model**: Data dasar pengguna seperti `title`, `groom_name`, `bride_name`, `akad_date`, `venue`, dll disimpan di dalam tabel `invitations`.
2. **Controller Logic**: Pengguna dapat mengubah data tersebut via route `invitations.update` yang dikelola oleh `Admin\InvitationController`.
3. **Theme Configuration Override**: Saat ini, konfigurasi seperti asset, font, dan warna masih ditarik statis dari `theme.json` bawaan milik *Theme* (sebagai *Single Source of Truth*). Belum ada mekanisme agar pengguna (user/client) bisa men-override sebagian dari *design token* (misalnya mengganti warna background sedikit) di level database atau menyimpannya sebagai konfigurasi kustom di model `Invitation` tanpa mengubah file `theme.json` utama.

*Analisis terhenti karena instruksi terpotong. Menunggu detail Task 1 dari pengguna...*
