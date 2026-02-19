# Clean Kode SKPD Command

## Deskripsi

Artisan command untuk membersihkan karakter carriage return (`\r`) dari field `kode_skpd` di tabel `skpd`.

## Penggunaan

### Menjalankan command (Dry Run)
Untuk melihat data yang akan diubah tanpa melakukan perubahan:

```bash
php artisan app:clean-kode-skpd --dry-run
```

### Menjalankan command (Update Sebenarnya)
Untuk membersihkan data:

```bash
php artisan app:clean-kode-skpd
```

Anda akan diminta untuk mengkonfirmasi perubahan sebelum data diupdate.

## Fitur

1. **Deteksi Carriage Return**: Command menggunakan raw query untuk mendeteksi karakter `\r` dalam field `kode_skpd`
2. **Dry Run Mode**: Opsi `--dry-run` untuk melihat data yang akan diubah tanpa melakukan perubahan
3. **Konfirmasi**: Prompt konfirmasi sebelum melakukan update untuk mencegah perubahan tidak sengaja
4. **Progress Bar**: Menampilkan progress bar saat mengupdate data
5. **Tabel Preview**: Menampilkan 20 data pertama yang akan diubah dalam format tabel
6. **Error Handling**: Menangani exception dengan pesan error yang jelas

## Cara Kerja

1. Mencari semua data di tabel `skpd` yang memiliki karakter carriage return (`\r`) di field `kode_skpd`
2. Menampilkan preview data yang akan diubah
3. Meminta konfirmasi user (jika tidak dalam dry-run mode)
4. Membersihkan karakter `\r` dan `\n` dari akhir string menggunakan fungsi `trim()`
5. Mengupdate data di database
6. Menampilkan ringkasan hasil

## Hasil

Command berhasil menghapus karakter carriage return dari **8 data** di tabel `skpd`:

| ID | Nama SKPD | Kode SKPD (Sebelum) | Kode SKPD (Setelah) |
|----|-----------|---------------------|---------------------|
| 39 | Bagian Kesejahteraan Rakyat dan Kemasyarakatan | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |
| 40 | Bagian Administrasi Pembangunan | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |
| 41 | Bagian Pengadaan Barang dan Jasa | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |
| 42 | Bagian Umum | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |
| 43 | Bagian Perekonomian dan Sumber Daya Alam | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |
| 44 | Bagian Pemerintahan | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |
| 45 | Bagian Hukum | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |
| 46 | Bagian Organisasi | 4.01.0.00.0.00.01.0000\r | 4.01.0.00.0.00.01.0000 |

## Catatan Penting

- Command ini menggunakan `trim($kode, "\r\n")` untuk menghapus karakter carriage return dan newline dari akhir string
- Carriage return (`\r`) biasanya muncul saat import data dari file Excel atau CSV yang dibuat di Windows
- Setelah dijalankan, tidak ada data dengan karakter carriage return yang tersisa

## Lokasi File

Command terletak di: `app/Console/Commands/CleanKodeSkpd.php`