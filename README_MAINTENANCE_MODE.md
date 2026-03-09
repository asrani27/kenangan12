# Maintenance Mode - KENANGAN

Fitur maintenance mode untuk mengatur tampilan halaman saat sistem sedang dalam perbaikan.

## 📋 Fitur

- Halaman maintenance mode yang menarik dengan animasi
- Kontrol untuk mengaktifkan/menonaktifkan maintenance mode
- Akses khusus untuk admin saat maintenance mode aktif
- Responsive design yang menyesuaikan dengan semua ukuran layar

## 🚀 Cara Menggunakan

### 1. Mengaktifkan Maintenance Mode

Ada beberapa cara untuk mengaktifkan maintenance mode:

#### Via Route (POST)
```bash
curl -X POST http://your-domain.com/maintenance/enable
```

#### Via Form di Dashboard
Tambahkan tombol di dashboard admin:
```blade
<form action="{{ route('maintenance.enable') }}" method="POST">
    @csrf
    <button type="submit">Aktifkan Maintenance Mode</button>
</form>
```

#### Via Artisan Command (Opsi Tambahan)
```bash
php artisan down
```

### 2. Menonaktifkan Maintenance Mode

#### Via Route (POST)
```bash
curl -X POST http://your-domain.com/maintenance/disable
```

#### Via Form di Dashboard
```blade
<form action="{{ route('maintenance.disable') }}" method="POST">
    @csrf
    <button type="submit">Nonaktifkan Maintenance Mode</button>
</form>
```

#### Via Artisan Command (Opsi Tambahan)
```bash
php artisan up
```

### 3. Mengecek Status Maintenance Mode

#### Via API (GET)
```bash
curl http://your-domain.com/maintenance/status
```

Response:
```json
{
    "maintenance_mode": true
}
```

## 📁 Struktur File

```
app/Http/Controllers/MaintenanceController.php   # Controller untuk maintenance
app/Http/Middleware/CheckMaintenance.php          # Middleware untuk mengecek maintenance
resources/views/maintenance.blade.php             # View halaman maintenance (custom)
resources/views/errors/503.blade.php              # View error 503 (Laravel built-in)
routes/web.php                                     # Route untuk maintenance
bootstrap/app.php                                  # Registrasi middleware
```

## ⚙️ Cara Kerja

Laravel memiliki dua cara untuk menampilkan halaman maintenance:

1. **Via `php artisan down` (Built-in Laravel)**:
   - Laravel otomatis akan menampilkan `resources/views/errors/503.blade.php`
   - File ini sudah disalin dari maintenance.blade.php
   - Tidak memerlukan konfigurasi tambahan

2. **Via Custom Middleware**:
   - Middleware `CheckMaintenance` mengecek file `storage/framework/down`
   - Jika maintenance aktif, redirect ke halaman maintenance
   - Mengizinkan akses ke `/maintenance`, `/login`, dan `/api/*`

## 🔄 Perbaikan Masalah

Jika halaman maintenance tidak muncul saat menjalankan `php artisan down`:

1. Pastikan file `resources/views/errors/503.blade.php` ada:
   ```bash
   ls -la resources/views/errors/503.blade.php
   ```

2. Jika tidak ada, buat manual:
   ```bash
   mkdir -p resources/views/errors
   cp resources/views/maintenance.blade.php resources/views/errors/503.blade.php
   ```

3. Clear cache:
   ```bash
   php artisan view:clear
   php artisan config:clear
   ```

4. Pastikan maintenance mode aktif:
   ```bash
   php artisan down
   ```

5. Akses halaman apapun, seharusnya muncul halaman maintenance

## 🔧 Rute yang Tersedia

| Rute | Method | Deskripsi | Middleware |
|------|--------|-----------|------------|
| `/maintenance` | GET | Menampilkan halaman maintenance | - |
| `/maintenance/status` | GET | Mengecek status maintenance mode | - |
| `/maintenance/enable` | POST | Mengaktifkan maintenance mode | auth |
| `/maintenance/disable` | POST | Menonaktifkan maintenance mode | auth |

## 🎨 Kustomisasi

### Mengubah Pesan Maintenance

Edit file `resources/views/maintenance.blade.php`:

```blade
<h1>Sedang Dalam Perbaikan</h1>
<p class="subtitle">Kami akan kembali segera</p>

<div class="message">
    <!-- Ubah pesan di sini -->
    Mohon maaf atas ketidaknyamanan ini. Sistem sedang dalam proses pemeliharaan...
</div>
```

### Mengubah Informasi Kontak

Edit file `resources/views/maintenance.blade.php`:

```blade
<div class="contact-info">
    <div class="contact-item">
        <i class="fas fa-envelope"></i>
        <span>support@kenangan.com</span>
    </div>
    <div class="contact-item">
        <i class="fas fa-phone"></i>
        <span>+62 812 3456 7890</span>
    </div>
</div>
```

### Mengubah Estimasi Waktu

Edit file `resources/views/maintenance.blade.php`:

```blade
<div class="info-box">
    <i class="fas fa-info-circle"></i>
    <span>Estimasi: 2 Jam</span> <!-- Ubah di sini -->
</div>
```

## 🔐 Keamanan

- Maintenance mode hanya dapat dikontrol oleh user yang sudah login
- Halaman maintenance tidak terpengaruh oleh auth middleware
- Login tetap dapat diakses saat maintenance mode aktif

## 💡 Tips

1. **Backup sebelum maintenance**: Selalu buat backup sebelum mengaktifkan maintenance mode
2. **Uji coba**: Coba akses halaman maintenance untuk memastikan tampilan sesuai
3. **Notifikasi**: Beri tahu pengguna sebelum mengaktifkan maintenance mode
4. **Monitor**: Pantau system setelah maintenance selesai

## 🐛 Troubleshooting

### Maintenance mode tidak berfungsi

Periksa apakah file `storage/framework/down` ada:
```bash
ls -la storage/framework/down
```

Jika ada, hapus untuk menonaktifkan:
```bash
rm storage/framework/down
```

### Halaman maintenance tidak muncul

Periksa middleware di `bootstrap/app.php`:
```php
$middleware->web(append: [
    \App\Http\Middleware\CheckMaintenance::class,
]);
```

### Tidak bisa login saat maintenance mode aktif

Login seharusnya tetap bisa diakses. Pastikan route login tidak diblokir di middleware `CheckMaintenance.php`:
```php
if ($isDown && !$request->is('maintenance*') && !$request->is('login')) {
    // ...
}
```

## 📝 Catatan Pengembang

- Maintenance mode menggunakan file `storage/framework/down` sebagai flag
- Middleware `CheckMaintenance` mengecek keberadaan file tersebut
- Controller `MaintenanceController` menangani enable/disable maintenance mode
- Halaman maintenance memiliki desain yang konsisten dengan aplikasi utama

## 🔄 Changelog

### v1.0.0 (2026-03-09)
- Fitur awal maintenance mode
- Halaman maintenance dengan animasi
- Controller untuk kontrol maintenance
- Middleware untuk mengecek maintenance
- Route untuk enable/disable/status

---

Dibuat untuk aplikasi KENANGAN © 2026