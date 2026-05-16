# 📂 Panduan Struktur Folder Proyek Rental Mobil

Halo! Dokumen ini dibuat khusus untuk memandu Anda (terutama jika Anda masih pemula) dalam memahami struktur folder proyek aplikasi Rental Mobil ini. Dengan memahami letak file dan folder, Anda akan lebih mudah melakukan *maintenance* (perawatan) atau pembaruan di masa mendatang.

Aplikasi ini dibangun menggunakan **Laravel** dan **Filament PHP** (sebagai panel admin).

Berikut adalah penjelasan folder-folder utama yang sering digunakan:

---

## 🛠️ Folder Aplikasi Utama (app/)
Di sinilah letak jantung aplikasi Anda. Logika bisnis, tampilan panel admin, dan pengaturan *database* ada di sini.

- **`app/Models/`**
  Ini adalah folder untuk "Model". Model berfungsi sebagai jembatan antara aplikasi dan *database* (tabel).
  - Contoh file: `Kendaraan.php`, `Booking.php`.
  - *Kapan mengubah ini?* Jika Anda menambahkan kolom baru di tabel *database* dan ingin aplikasi bisa membaca/menyimpannya.

- **`app/Filament/`**
  Ini adalah folder khusus untuk mengatur tampilan Panel Admin (Dashboard, Menu, Form, dll).
  - **`Resources/`**: Mengatur menu CRUD (Create, Read, Update, Delete) yang biasa ada di sebelah kiri panel admin.
    - `KendaraanResource.php`: Mengatur form tambah kendaraan dan tabel list kendaraan.
    - `TransaksiResource.php`: Mengatur halaman riwayat transaksi rental.
  - **`Pages/`**: Mengatur halaman kustom yang bukan sekadar tabel biasa.
    - `BookingKatalog.php`: Mengatur tampilan daftar kendaraan yang bisa dibooking beserta form input pesanan.

---

## 🌐 Folder Konfigurasi dan Lingkungan
- **`.env`** (File di luar folder, langsung di folder utama)
  File ini berisi pengaturan kunci seperti koneksi ke *database*, URL aplikasi (misal `http://rentalmobil.test`), dan pengaturan email.
  - *Catatan:* File ini sangat rahasia dan tidak boleh dibagikan sembarangan.

- **`config/`**
  Berisi berbagai pengaturan bawaan aplikasi (zona waktu, bahasa, *timezone*, dll).
  - Contoh: `config/app.php` digunakan untuk mengatur *timezone* menjadi `Asia/Jakarta`.

---

## 🗄️ Folder Database (database/)
Folder ini bertugas membangun dan mengelola isi *database* Anda.

- **`database/migrations/`**
  Berisi file *migration* (migrasi). Anggap saja ini sebagai cetak biru (blueprint) untuk membuat tabel di *database*.
  - *Kapan menggunakan ini?* Jika Anda butuh menambah tabel baru (misal: tabel `kategori`) atau kolom baru (misal: kolom `status` di tabel `kendaraans`).
  
- **`database/seeders/`**
  Tempat untuk membuat data palsu atau data awal secara otomatis ke dalam *database*.

---

## 🖼️ Folder File Publik & Penyimpanan (public/ & storage/)
- **`public/`**
  Ini adalah folder yang bisa diakses langsung oleh publik (pengunjung web). Biasanya berisi file gambar (aset), file CSS, dan file Javascript (JS).

- **`storage/`**
  Folder ini digunakan oleh aplikasi untuk menyimpan file sementara (cache), catatan *error* (log), dan file unggahan pengguna (seperti gambar mobil atau KTP pelanggan).
  - **`storage/app/public/`**: Ini adalah tempat asli dimana foto-foto KTP, KK, SIM, dan kendaraan disimpan.
  - **`storage/logs/`**: Jika aplikasi mengalami *error* (layar putih atau 500 Server Error), Anda bisa mengecek file `laravel.log` di sini untuk melihat penyebab *error*-nya.

---

## 🚦 Folder Rute Akses (routes/)
- **`routes/web.php`**
  File ini mengatur *URL* (link) apa saja yang bisa diakses di aplikasi Anda. Namun, karena Anda menggunakan Filament PHP, sebagian besar rute admin sudah dibuat secara otomatis oleh sistem, sehingga file ini jarang diubah kecuali Anda ingin membuat halaman publik (*landing page* atau profil perusahaan).

---

## 💡 Tips untuk Pemula (Maintenance)

1. **Gambar Tidak Muncul?**
   - Pastikan URL di file `.env` (bagian `APP_URL`) sudah benar sesuai dengan nama domain lokal Anda (misal `http://rentalmobil.test`).
   - Pastikan Anda sudah menjalankan perintah `php artisan storage:link` di terminal agar folder `storage` terhubung ke folder `public`.

2. **Ada Error Waktu Upload Gambar?**
   - Pastikan *size* (ukuran) gambar tidak melebihi batas (biasanya diatur `maxSize(2048)` alias 2MB).
   - Pastikan folder `storage/` memiliki izin (*permission*) untuk ditulisi oleh server.

3. **Ingin Menambah Menu Baru di Admin?**
   - Buat Model dan Migration-nya terlebih dahulu.
   - Jalankan perintah terminal: `php artisan make:filament-resource NamaMenuResource`
   - Buka folder `app/Filament/Resources/` lalu atur kolom dan form-nya di sana.

Semoga panduan ini membantu Anda dalam mengelola aplikasi Rental Mobil! Selamat mencoba! 🚀
