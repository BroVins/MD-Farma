# Pemasangan perbaikan audit dan integritas

1. Cadangkan database `md_farma`.
2. Impor `md-farma-integrity-audit-manual.sql` melalui phpMyAdmin.
3. Ekstrak ZIP paste & replace ke folder utama proyek.
4. Pertahankan file `.env` milik instalasi; jangan menggantinya dengan `.env.example`.
5. Jalankan `php artisan optimize:clear`.
6. Uji recovery perangkat, pengiriman pesan, penutupan, dan pengaktifan kembali konsultasi.

SQL menambahkan:

- `consultation_status_logs`
- `consultation_access_logs`
- unique index untuk `admins.username` jika tidak ada username duplikat

Akun admin lama tidak dihapus atau diganti. Seeder baru hanya membaca `ADMIN_USERNAME` dan `ADMIN_PASSWORD` bila dijalankan secara manual.
