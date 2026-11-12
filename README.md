# CRUD PHP + MySQL/MariaDB (Data Mahasiswa)

## Cara menjalankan (XAMPP/Laragon)
1. Salin folder ini ke `htdocs/crud-php` (XAMPP) atau `www/crud-php` (Laragon).
2. Jalankan Apache dan MySQL/MariaDB.
3. Buka phpMyAdmin → tab Import → pilih `database.sql`.
4. Sesuaikan user/password di `config.php` bila perlu.
5. Buka http://localhost/crud-php/

## Struktur
- database.sql : skema + data contoh
- config.php   : koneksi PDO, CSRF, helper
- index.php    : daftar + cari + paginasi (Read)
- form.php     : tambah (Create) & edit (Update)
- hapus.php    : hapus (Delete)
- style.css    : tampilan
