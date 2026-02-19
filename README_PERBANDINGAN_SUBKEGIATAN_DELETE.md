# Cara Menggunakan Fitur Penghapusan Data

## Deskripsi
Perintah `app:perbandingan-subkegiatan` telah diperbarui dengan fitur untuk menghapus data yang ditemukan di model Subkegiatan tetapi tidak ada di Subkegiatan2025.

## Penggunaan Dasar

### 1. Lihat Perbandingan Data (Tanpa Menghapus)
```bash
php artisan app:perbandingan-subkegiatan
```
Perintah ini hanya menampilkan perbedaan data tanpa menghapus apapun.

### 2. Hapus Data yang Ditemukan
```bash
php artisan app:perbandingan-subkegiatan --delete
```
Perintah ini akan:
1. Membandingkan data antara Subkegiatan dan Subkegiatan2025
2. Menampilkan hasil perbandingan
3. Meminta konfirmasi sebelum menghapus
4. Menghapus data yang ada di Subkegiatan tetapi tidak ada di Subkegiatan2025

## Contoh Output

### Tanpa Opsi --delete
```
========================================
RINGKASAN
========================================
Data unik di Subkegiatan: 15
Data unik di Subkegiatan2025: 0
Duplikat di Subkegiatan: 0 record
Duplikat di Subkegiatan2025: 0 record
========================================
```

### Dengan Opsi --delete
```
========================================
MENGHAPUS DATA
========================================

Jumlah data yang akan dihapus: 15

Apakah Anda yakin ingin menghapus 15 data dari Subkegiatan? (yes/no) [no]:
```

## Fitur Keamanan

1. **Konfirmasi Diperlukan**: Perintah akan selalu meminta konfirmasi sebelum menghapus data
2. **Hapus Berdasarkan ID**: Data dihapus berdasarkan ID yang ditemukan dari perbandingan
3. **Error Handling**: Jika terjadi kesalahan, pesan error akan ditampilkan dengan jelas
4. **Cancellable**: Anda dapat membatalkan penghapusan dengan menjawab "no" pada konfirmasi

## Data yang Dihapus

Data yang dihapus adalah data yang:
- Ada di tabel `subkegiatan`
- Tidak ada di tabel `subkegiatan2025`
- Dihapus berdasarkan 5 field kunci:
  - `kode_skpd`
  - `kode_subunit`
  - `kode_program`
  - `kode_kegiatan`
  - `kode` (kode_subkegiatan)

## Catatan Penting

- Pastikan untuk backup database sebelum menjalankan perintah dengan opsi `--delete`
- Penghapusan tidak dapat dibatalkan setelah dikonfirmasi
- Perintah menggunakan `whereIn` untuk efisiensi saat menghapus banyak data sekaligus