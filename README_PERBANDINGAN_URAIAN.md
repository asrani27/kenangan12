# Perbandingan Uraian

## Deskripsi

Artisan command untuk membandingkan data antara model `Uraian` dan `Uraian2026` berdasarkan field-field tertentu.

## Command

```bash
php artisan app:perbandingan-uraian
```

## Opsi

### Tanpa Opsi (Default)
Hanya menampilkan hasil perbandingan tanpa mengubah data apapun.

```bash
php artisan app:perbandingan-uraian
```

### `--delete`
Hapus data yang ditemukan di Uraian (data yang tidak ada di Uraian2026).

```bash
php artisan app:perbandingan-uraian --delete
```

### `--add`
Tambahkan data dari Uraian2026 ke Uraian (data yang ada di Uraian2026 tapi tidak ada di Uraian).

```bash
php artisan app:perbandingan-uraian --add
```

### `--sync`
Lakukan sinkronisasi penuh: hapus data yang berlebih dari Uraian dan tambahkan data yang hilang dari Uraian2026. Ini setara dengan menggunakan `--delete --add` secara bersamaan.

```bash
php artisan app:perbandingan-uraian --sync
```

### `--clean-duplicates`
Hapus data duplikat dari Uraian. Jika ada beberapa record dengan kombinasi field yang sama (tahun, kode_skpd, kode_subunit, kode_program, kode_kegiatan, kode_subkegiatan, kode_rekening), maka hanya record dengan ID terbaru yang akan dipertahankan.

```bash
php artisan app:perbandingan-uraian --clean-duplicates
```

## Field yang Digunakan untuk Perbandingan

Command ini membandingkan data berdasarkan 7 field berikut:
1. `tahun`
2. `kode_skpd`
3. `kode_subunit`
4. `kode_program`
5. `kode_kegiatan`
6. `kode_subkegiatan`
7. `kode_rekening`

## Output

Command akan menampilkan:

### 1. Informasi Field yang Tersedia
Menampilkan semua field yang ada di kedua model.

### 2. Total Data
Menampilkan jumlah total data di masing-masing model.

### 3. Informasi Duplikat
Menampilkan jumlah record duplikat di setiap model berdasarkan kombinasi 7 field perbandingan.

### 4. Hasil Perbandingan
- **Data yang ada di Uraian TAPI TIDAK ada di Uraian2026**: Menampilkan data unik di Uraian
- **Data yang ada di Uraian2026 TAPI TIDAK ada di Uraian**: Menampilkan data unik di Uraian2026

Setiap kategori menampilkan maksimal 10 data pertama untuk mencegah output terlalu panjang.

### 5. Ringkasan
Ringkasan akhir yang berisi:
- Jumlah data unik di Uraian
- Jumlah data unik di Uraian2026
- Jumlah duplikat di Uraian
- Jumlah duplikat di Uraian2026

## Penghapusan dan Penambahan Data

### Penghapusan Data (--delete)
Jika menggunakan opsi `--delete`, command akan:
1. Mengidentifikasi data yang ada di Uraian tapi tidak ada di Uraian2026
2. Meminta konfirmasi sebelum menghapus
3. Menghapus data yang ditemukan dari tabel Uraian

**⚠️ Peringatan**: Penghapusan bersifat permanen. Pastikan untuk backup data sebelum menggunakan opsi `--delete`.

### Penambahan Data (--add)
Jika menggunakan opsi `--add`, command akan:
1. Mengidentifikasi data yang ada di Uraian2026 tapi tidak ada di Uraian
2. Meminta konfirmasi sebelum menambahkan
3. Menambahkan data yang ditemukan dari Uraian2026 ke tabel Uraian
4. Menghapus field `id`, `created_at`, dan `updated_at` agar dapat auto-increment

### Sinkronisasi Penuh (--sync)
Jika menggunakan opsi `--sync`, command akan:
1. Menjalankan proses penghapusan (sama seperti `--delete`)
2. Menjalankan proses penambahan (sama seperti `--add`)
3. Meminta konfirmasi untuk masing-masing proses

Ini akan membuat tabel Uraian sinkron dengan Uraian2026.

### Pembersihan Duplikat (--clean-duplicates)
Jika menggunakan opsi `--clean-duplicates`, command akan:
1. Mencari semua data duplikat berdasarkan 7 field perbandingan
2. Meminta konfirmasi sebelum menghapus
3. Menghapus duplikat dan hanya mempertahankan record dengan ID terbaru untuk setiap kombinasi field

**⚠️ Peringatan**: Penghapusan duplikat bersifat permanen. Pastikan untuk backup data sebelum menggunakan opsi `--clean-duplicates`.

## Contoh Output

```
========================================
Memulai perbandingan data Uraian...
========================================

Mengambil sample data...
========================================
FIELD YANG TERSEDIA
========================================
Field di Uraian: id, tahun, kode_skpd, kode_subunit, ...
Field di Uraian2026: id, tahun, kode_skpd, kode_subunit, ...
========================================
Field untuk perbandingan: tahun, kode_skpd, kode_subunit, kode_program, kode_kegiatan, kode_subkegiatan, kode_rekening
========================================

Menghitung total data...
Total data di Uraian: 11088
Total data di Uraian2026: 10793

Menganalisis duplikat...
Duplikat di Uraian: 165 record
Duplikat di Uraian2026: 0 record

Menganalisis perbedaan data...

Membuat indeks dari Uraian2026...
Processed 1000 records...
...
Total indeks Uraian2026: 10793

Mencari data unik di Uraian...
Data unik di Uraian: 585

Membuat indeks dari Uraian...
Processed 1000 records...
...
Total indeks Uraian: 11088

Mencari data unik di Uraian2026...
Data unik di Uraian2026: 382

========================================
HASIL PERBANDINGAN
========================================

Data yang ada di Uraian TAPI TIDAK ada di Uraian2026:
Total: 585
[Detail data ditampilkan di sini...]

Data yang ada di Uraian2026 TAPI TIDAK ada di Uraian:
Total: 382
[Detail data ditampilkan di sini...]

INFORMASI DUPLIKAT:

Duplikat di Uraian:
Total: 165 record duplikat

Duplikat di Uraian2026:
Tidak ada duplikat.

========================================
RINGKASAN
========================================
Data unik di Uraian: 585
Data unik di Uraian2026: 382
Duplikat di Uraian: 165 record
Duplikat di Uraian2026: 0 record
========================================
```

## Catatan Teknis

- Command menggunakan teknik **chunking** untuk memproses data dalam batch 1000 record, sehingga efisien dalam penggunaan memory
- Unique key dibuat menggunakan MD5 hash dari kombinasi 7 field perbandingan
- Jika salah satu field perbandingan tidak tersedia, command akan menggunakan semua field sebagai fallback