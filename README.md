# Manajemen - Koperasi

## Deskripsi
Sistem Manajemen Inventaris Koperasi adalah aplikasi berbasis web yang dirancang untuk mendigitalisasi proses pengelolaan persediaan barang di koperasi yang sebelumnya dilakukan secara manual menggunakan buku atau Microsoft Excel. Sistem ini dibangun menggunakan **Laravel 12** dengan arsitektur three-tier (presentasi, logika bisnis, dan data) serta basis data **MySQL**.

Aplikasi ini bertujuan untuk:
- **Digitalisasi dan Otomatisasi Stok**: Menggantikan pencatatan manual dengan sistem digital yang dapat menghitung dan memperbarui jumlah persediaan barang secara otomatis dan terintegrasi setiap kali transaksi divalidasi.
- **Mengatasi Permasalahan Operasional Gudang**: Meminimalkan ketidaksesuaian antara catatan stok dan jumlah fisik barang, mencegah keterlambatan pencatatan barang masuk dan keluar, serta mempermudah pemantauan ketersediaan barang.

## Fitur
| Fitur | Keterangan | Status |
|---|---|---|
| Login | Autentikasi dengan email atau username, dilengkapi pembatasan percobaan login | Tersedia |
| Data Barang | Tambah, ubah, hapus, dan cari data barang (kode, nama, satuan, stok) | Tersedia |
| Barang Masuk (Penerimaan) | Pencatatan penerimaan dari supplier; stok bertambah otomatis setelah transaksi divalidasi | Tersedia |
| Barang Keluar (Pengiriman) | Pencatatan pengeluaran barang ke anggota; stok berkurang otomatis setelah transaksi divalidasi | Dalam pengembangan |

Peran pengguna:
- **Admin**: memiliki hak akses penuh dan dapat melihat data penerimaan serta pengeluaran yang dicatat karyawan.
- **Karyawan**: mencatat barang masuk dan barang keluar sesuai aktivitas harian.

Alur status transaksi: `draft` → `done` (divalidasi, stok berubah) atau `cancelled` (dibatalkan).

## Anggota Tim
- Wahyu Ramadan - Peran
- Adelia Cyntia Renata - Peran
- Nazwa Nur Hafizhah - Peran
- Zalfa Putri Sopyandi - Peran

## Teknologi
- PHP 8.2 atau lebih baru
- Laravel 12 (dengan Laravel Breeze untuk autentikasi)
- MySQL
- Blade (template engine)
- Tailwind CSS
- Alpine.js
- Vite
- Composer dan Node.js (npm)

## Struktur Dokumentasi
- docs/SDD: Dokumen Software Design Description (desain perangkat lunak).
- docs/STD: Dokumen Software Testing Document (pengujian perangkat lunak).

## Struktur Folder Utama
```
app/
  Enums/          Enum peran, tipe mitra, tipe dan status transaksi
  Http/           Controller, Form Request, dan Middleware
  Models/         Model Eloquent (User, Partner, Product, StockTransaction, StockTransactionItem)
  Services/       Logika bisnis transaksi stok
database/
  migrations/     Skema tabel
  seeders/        Data awal (admin, mitra, dan barang contoh)
resources/views/  Tampilan Blade
routes/           Definisi route
```

## Persiapan
Pastikan perangkat sudah terpasang **XAMPP** (Apache dan MySQL), **PHP 8.2+**, **Composer**, dan **Node.js**.

1. Clone repository, lalu masuk ke folder proyek.
   ```bash
   git clone <url-repository>
   cd Manajemen-Koperasi
   ```
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Buat database kosong, misalnya `manajemen_koperasi`, melalui phpMyAdmin.
4. Pasang dependensi.
   ```bash
   composer install
   npm install
   ```
5. Salin file environment dan buat application key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pada Windows (Command Prompt) gunakan `copy .env.example .env`.
6. Ubah konfigurasi database pada file `.env`.
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=manajemen_koperasi
   DB_USERNAME=root
   DB_PASSWORD=
   ```
7. Jalankan migrasi dan seeder.
   ```bash
   php artisan migrate --seed
   ```
8. Jalankan aplikasi. Gunakan dua terminal.
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```
9. Buka `http://127.0.0.1:8000` di browser.

## Akun Awal
Seeder membuat satu akun admin untuk keperluan pengembangan:

| Email | Password |
|---|---|
| admin@koperasi.test | password |

Akun dapat diubah lewat variabel `ADMIN_EMAIL` dan `ADMIN_PASSWORD` di `.env` sebelum menjalankan seeder. Ganti password bawaan sebelum aplikasi dipakai di luar lingkungan lokal.

## Catatan
- Seluruh seeder berada dalam satu file `database/seeders/DatabaseSeeder.php`. Jalankan dengan `php artisan db:seed` atau `php artisan migrate:fresh --seed`. Perintah `db:seed --class=AdminSeeder` tidak dapat digunakan.
- `migrate:fresh` menghapus seluruh tabel beserta datanya. Jangan dijalankan pada data yang masih dibutuhkan.
- Petunjuk instalasi, konfigurasi, dan penggunaan aplikasi akan diperbarui sesuai dengan perkembangan dan kebutuhan proyek.