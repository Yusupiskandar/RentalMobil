# ISSUE: Pengembangan Modul Kota - Form Input & Upload Thumbnail

## 📝 Deskripsi
Dokumen ini merincikan rencana penambahan fitur pada menu pengelolaan Kota. Fitur baru ini memungkinkan admin untuk menambahkan nama kota serta mengunggah gambar pendukung (thumbnail) untuk representasi visual.

---

## 🛠 Rencana Pengerjaan (Task List)

### 1. Implementasi Form Input Kota
- [ ] Menambahkan elemen `<input type="text">` untuk atribut Nama Kota.
- [ ] Menambahkan validasi input (Wajib diisi, karakter alfabet, panjang minimal/maksimal).
- [ ] Menyiapkan field `nama_kota` pada database.
- [ ] Menghubungkan input dengan logika backend (Controller/API).

### 2. Implementasi Upload Gambar Thumbnail
- [ ] Membuat komponen unggah file pada form Kota.
- [ ] Menambahkan filter tipe file (JPG, PNG, WebP).
- [ ] Implementasi sistem penyimpanan file (Storage/Cloudinary/S3).
- [ ] Menampilkan *Preview Image* sebelum proses submit.
- [ ] Menyimpan path/URL gambar ke kolom `thumbnail` di database.

---

## 🎨 Spesifikasi Teknis
- **Field Nama:** `text_city_name` (Varchar 255)
- **Field Gambar:** `file_thumbnail` (Blob/String Path)
- **Validasi Gambar:** Maksimal 2MB, aspek rasio disarankan 16:9.

---

## ✅ Kriteria Penerimaan (Acceptance Criteria)
1. Data Kota tersimpan dengan benar ke database melalui form.
2. File gambar berhasil terunggah dan dapat diakses/ditampilkan kembali.
3. Form menolak input jika nama kota kosong atau format file bukan gambar.
4. UI/UX konsisten dengan modul yang sudah ada.

---
**Label:** `enhancement`, `ui-development`
**Priority:** `Medium`
