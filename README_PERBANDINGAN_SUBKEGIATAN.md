# Command Artisan: Perbandingan Subkegiatan

## Deskripsi
Command artisan untuk membandingkan data antara model `Subkegiatan` dan `Subkegiatan2025`, mencari selisih menggunakan metode diff berdasarkan 5 field kode (kode_skpd, kode_subunit, kode_program, kode_kegiatan, dan kode_subkegiatan), dan mendeteksi duplikat.

## Cara Menggunakan

### Menjalankan command:
```bash
php artisan app:perbandingan-subkegiatan
```

## Output Command

Command akan menampilkan informasi berikut:

### 1. Ringkasan Data
- Total data di model Subkegiatan
- Total data di model Subkegiatan2025

### 2. Field yang Tersedia
- Menampilkan semua field yang ada di setiap model untuk referensi

### 3. Hasil Perbandingan
- **Data unik di Subkegiatan**: Data yang ada di Subkegiatan tapi TIDAK ada di Subkegiatan2025
- **Data unik di Subkegiatan2025**: Data yang ada di Subkegiatan2025 tapi TIDAK ada di Subkegiatan

### 4. Informasi Duplikat
- **Duplikat di Subkegiatan**: Jumlah dan contoh data duplikat berdasarkan field `kode` dan `nama`
- **Duplikat di Subkegiatan2025**: Jumlah dan contoh data duplikat berdasarkan field `kode` dan `nama`

### 5. Ringkasan Akhir
- Total data unik di masing-masing model
- Total duplikat di masing-masing model

## Contoh Output

```
========================================
Memulai perbandingan data Subkegiatan...
========================================

Mengambil data dari model Subkegiatan...
Total data di Subkegiatan: 3256

Mengambil data dari model Subkegiatan2025...
Total data di Subkegiatan2025: 3241

========================================
FIELD YANG TERSEDIA
========================================
Field di Subkegiatan: id, tahun, kode_skpd, kode_subunit, kode_program, kode_kegiatan, kode, nama, created_at, updated_at, nip_pptk
Field di Subkegiatan2025: id, tahun, kode_skpd, kode_subunit, kode_program, kode_kegiatan, kode, nama, created_at, updated_at, nip_pptk
========================================

Menganalisis duplikat...

Menganalisis perbedaan data...

========================================
HASIL PERBANDINGAN
========================================

Data yang ada di Subkegiatan TAPI TIDAK ada di Subkegiatan2025:
Total: 0

Tidak ada perbedaan.

Data yang ada di Subkegiatan2025 TAPI TIDAK ada di Subkegiatan:
Total: 0

Tidak ada perbedaan.

INFORMASI DUPLIKAT:

Duplikat di Subkegiatan:
Total: 1668 record duplikat
--------------------------------------------------------------------------------
Duplikat #1:
  Kode: 1.02.02.2.01.0014
  Nama: Pengadaan Alat Kesehatan/Alat Penunjang Medik Fasilitas Pelayanan Kesehatan
--------------------------------------------------------------------------------
Duplikat #2:
  Kode: 1.02.02.2.01.0014
  Nama: Pengadaan Alat Kesehatan/Alat Penunjang Medik Fasilitas Pelayanan Kesehatan
--------------------------------------------------------------------------------
Duplikat #3:
  Kode: 1.02.02.2.01.0023
  Nama: Pengadaan Obat, Bahan Habis Pakai, Bahan Medis Habis Pakai,, Vaksin, Makanan dan Minuman di Fasilitas Kesehatan
  ... dan 1665 duplikat lainnya

Duplikat di Subkegiatan2025:
Total: 1651 record duplikat
--------------------------------------------------------------------------------
Duplikat #1:
  Kode: 1.02.02.2.01.0014
  Nama: Pengadaan Alat Kesehatan/Alat Penunjang Medik Fasilitas Pelayanan Kesehatan
--------------------------------------------------------------------------------
Duplikat #2:
  Kode: 1.02.02.2.01.0014
  Nama: Pengadaan Alat Kesehatan/Alat Penunjang Medik Fasilitas Pelayanan Kesehatan
--------------------------------------------------------------------------------
Duplikat #3:
  Kode: 1.02.02.2.01.0023
  Nama: Pengadaan Obat, Bahan Habis Pakai, Bahan Medis Habis Pakai,, Vaksin, Makanan dan Minuman di Fasilitas Kesehatan
  ... dan 1648 duplikat lainnya

========================================
RINGKASAN
========================================
Data unik di Subkegiatan: 0
Data unik di Subkegiatan2025: 0
Duplikat di Subkegiatan: 1668 record
Duplikat di Subkegiatan2025: 1651 record
========================================
```

## Fitur Utama

1. **Perbandingan Berbasis 5 Field Kode**: Menggunakan kombinasi field `kode_skpd`, `kode_subunit`, `kode_program`, `kode_kegiatan`, dan `kode` (kode_subkegiatan) sebagai kunci untuk identifikasi data unik
2. **Deteksi Duplikat**: Menemukan data yang duplikat dalam masing-masing model berdasarkan 5 field kode
3. **Tampilan Terbatas**: Menampilkan maksimal 10 data unik dan 3 contoh duplikat untuk menghindari output yang terlalu panjang
4. **Informasi Lengkap**: Menampilkan semua field yang tersedia di setiap model
5. **Ringkasan**: Memberikan ringkasan akhir yang mudah dipahami

## File Lokasi
Command ini terletak di: `app/Console/Commands/PerbandinganSubkegiatan.php`

## Catatan Penting

- Data dianggap unik jika kombinasi 5 field kode (`kode_skpd`, `kode_subunit`, `kode_program`, `kode_kegiatan`, `kode`) tidak ditemukan di model lain
- Data dianggap duplikat jika ada lebih dari satu record dengan kombinasi 5 field kode yang sama dalam model yang sama
- Command akan menampilkan maksimal 10 data unik dan 3 contoh duplikat untuk menjaga output tetap terbaca
- Perbandingan ini akurat untuk menemukan selisih 15 data seperti contoh: 3256 (Subkegiatan) vs 3241 (Subkegiatan2025) = 15 record selisih
