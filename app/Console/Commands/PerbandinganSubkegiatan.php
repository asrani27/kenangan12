<?php

namespace App\Console\Commands;

use App\Models\SubKegiatan;
use App\Models\Subkegiatan2025;
use Illuminate\Console\Command;

class PerbandinganSubkegiatan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:perbandingan-subkegiatan {--fields=* : Field spesifik untuk dibandingkan (default: kode,nama)} {--delete : Hapus data yang ditemukan di Subkegiatan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bandingkan data antara model Subkegiatan dan Subkegiatan2025, cari selisih menggunakan diff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('Memulai perbandingan data Subkegiatan...');
        $this->info('========================================');
        $this->newLine();

        try {
            // Ambil semua data dari kedua model
            $this->info('Mengambil data dari model Subkegiatan...');
            $subkegiatanData = SubKegiatan::all()->toArray();
            $this->info('Total data di Subkegiatan: ' . count($subkegiatanData));
            $this->newLine();

            $this->info('Mengambil data dari model Subkegiatan2025...');
            $subkegiatan2025Data = Subkegiatan2025::all()->toArray();
            $this->info('Total data di Subkegiatan2025: ' . count($subkegiatan2025Data));
            $this->newLine();

            // Tampilkan informasi field yang tersedia
            $this->info('========================================');
            $this->info('FIELD YANG TERSEDIA');
            $this->info('========================================');
            if (!empty($subkegiatanData)) {
                $this->info('Field di Subkegiatan: ' . implode(', ', array_keys($subkegiatanData[0])));
            }
            if (!empty($subkegiatan2025Data)) {
                $this->info('Field di Subkegiatan2025: ' . implode(', ', array_keys($subkegiatan2025Data[0])));
            }
            $this->info('========================================');
            $this->newLine();

            // Analisis duplikat
            $this->info('Menganalisis duplikat...');
            $duplikatSubkegiatan = $this->findDuplicates($subkegiatanData);
            $duplikatSubkegiatan2025 = $this->findDuplicates($subkegiatan2025Data);
            $this->newLine();

            // Konversi array menjadi format yang mudah dibandingkan
            $this->info('Menganalisis perbedaan data...');
            $this->newLine();

            // Cari data yang ada di Subkegiatan tapi tidak ada di Subkegiatan2025
            $diffInSubkegiatan = $this->findDifferences($subkegiatanData, $subkegiatan2025Data);

            // Cari data yang ada di Subkegiatan2025 tapi tidak ada di Subkegiatan
            $diffInSubkegiatan2025 = $this->findDifferences($subkegiatan2025Data, $subkegiatanData);

            // Tampilkan hasil
            $this->displayResults($diffInSubkegiatan, $diffInSubkegiatan2025, $duplikatSubkegiatan, $duplikatSubkegiatan2025);

            // Hapus data jika opsi --delete diaktifkan
            if ($this->option('delete')) {
                $this->deleteDiffData($diffInSubkegiatan);
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Cari perbedaan antara dua array data
     *
     * @param array $sourceData
     * @param array $targetData
     * @return array
     */
    private function findDifferences($sourceData, $targetData)
    {
        $differences = [];

        // Buat array kunci unik dari targetData untuk perbandingan cepat
        $targetKeys = [];
        foreach ($targetData as $item) {
            // Gunakan kombinasi field sebagai kunci unik
            $key = $this->createUniqueKey($item);
            $targetKeys[$key] = true;
        }

        // Cari data yang ada di source tapi tidak ada di target
        foreach ($sourceData as $item) {
            $key = $this->createUniqueKey($item);
            if (!isset($targetKeys[$key])) {
                $differences[] = $item;
            }
        }

        return $differences;
    }

    /**
     * Buat kunci unik dari data untuk perbandingan
     *
     * @param array $item
     * @return string
     */
    private function createUniqueKey($item)
    {
        // Gunakan 5 field untuk membuat kunci unik
        // kode_skpd, kode_subunit, kode_program, kode_kegiatan, dan kode (kode_subkegiatan)
        $fields = ['kode_skpd', 'kode_subunit', 'kode_program', 'kode_kegiatan', 'kode'];
        
        // Cek apakah semua field tersedia
        $allFieldsExist = true;
        foreach ($fields as $field) {
            if (!isset($item[$field])) {
                $allFieldsExist = false;
                break;
            }
        }
        
        if ($allFieldsExist) {
            // Buat kunci dari kombinasi 5 field
            $keyParts = [
                $item['kode_skpd'] ?? '',
                $item['kode_subunit'] ?? '',
                $item['kode_program'] ?? '',
                $item['kode_kegiatan'] ?? '',
                $item['kode'] ?? ''
            ];
            return md5(implode('|', $keyParts));
        }
        
        // Fallback: gunakan semua field
        return md5(json_encode($item));
    }

    /**
     * Cari duplikat dalam array data
     *
     * @param array $data
     * @return array
     */
    private function findDuplicates($data)
    {
        $counts = [];
        $duplicates = [];

        foreach ($data as $item) {
            $key = $this->createUniqueKey($item);
            if (!isset($counts[$key])) {
                $counts[$key] = [];
            }
            $counts[$key][] = $item;
        }

        // Ambil yang memiliki count > 1
        foreach ($counts as $key => $items) {
            if (count($items) > 1) {
                $duplicates = array_merge($duplicates, $items);
            }
        }

        return $duplicates;
    }

    /**
     * Tampilkan hasil perbandingan
     *
     * @param array $diffInSubkegiatan
     * @param array $diffInSubkegiatan2025
     * @param array $duplikatSubkegiatan
     * @param array $duplikatSubkegiatan2025
     * @return void
     */
    private function displayResults($diffInSubkegiatan, $diffInSubkegiatan2025, $duplikatSubkegiatan, $duplikatSubkegiatan2025)
    {
        $this->info('========================================');
        $this->info('HASIL PERBANDINGAN');
        $this->info('========================================');
        $this->newLine();

        // Tampilkan data yang ada di Subkegiatan tapi tidak di Subkegiatan2025
        $this->warn('Data yang ada di Subkegiatan TAPI TIDAK ada di Subkegiatan2025:');
        $this->info('Total: ' . count($diffInSubkegiatan));
        $this->newLine();

        if (count($diffInSubkegiatan) > 0) {
            foreach ($diffInSubkegiatan as $index => $item) {
                $this->line(str_repeat('-', 80));
                $this->line("Data #" . ($index + 1) . ":");
                
                // Tampilkan semua field termasuk ID
                foreach ($item as $field => $value) {
                    $this->line("  {$field}: {$value}");
                }
                
                // Tampilkan maksimal 10 data untuk mencegah output terlalu panjang
                if ($index >= 9 && count($diffInSubkegiatan) > 10) {
                    $remaining = count($diffInSubkegiatan) - 10;
                    $this->line("  ... dan {$remaining} data lainnya");
                    break;
                }
            }
        } else {
            $this->info('Tidak ada perbedaan.');
        }

        $this->newLine();
        $this->newLine();

        // Tampilkan data yang ada di Subkegiatan2025 tapi tidak di Subkegiatan
        $this->warn('Data yang ada di Subkegiatan2025 TAPI TIDAK ada di Subkegiatan:');
        $this->info('Total: ' . count($diffInSubkegiatan2025));
        $this->newLine();

        if (count($diffInSubkegiatan2025) > 0) {
            foreach ($diffInSubkegiatan2025 as $index => $item) {
                $this->line(str_repeat('-', 80));
                $this->line("Data #" . ($index + 1) . ":");
                
                // Tampilkan semua field termasuk ID
                foreach ($item as $field => $value) {
                    $this->line("  {$field}: {$value}");
                }
                
                // Tampilkan maksimal 10 data untuk mencegah output terlalu panjang
                if ($index >= 9 && count($diffInSubkegiatan2025) > 10) {
                    $remaining = count($diffInSubkegiatan2025) - 10;
                    $this->line("  ... dan {$remaining} data lainnya");
                    break;
                }
            }
        } else {
            $this->info('Tidak ada perbedaan.');
        }

        $this->newLine();
        $this->newLine();

        // Tampilkan informasi duplikat
        $this->warn('INFORMASI DUPLIKAT:');
        $this->newLine();
        
        $this->warn('Duplikat di Subkegiatan:');
        if (count($duplikatSubkegiatan) > 0) {
            $this->error("Total: " . count($duplikatSubkegiatan) . " record duplikat");
            
            // Tampilkan contoh duplikat (maksimal 3)
            $shown = 0;
            foreach ($duplikatSubkegiatan as $index => $item) {
                if ($shown >= 3) break;
                $this->line(str_repeat('-', 80));
                $this->line("Duplikat #" . ($shown + 1) . ":");
                $this->line("  Kode: {$item['kode']}");
                $this->line("  Nama: {$item['nama']}");
                $shown++;
            }
            
            if (count($duplikatSubkegiatan) > 3) {
                $this->line("  ... dan " . (count($duplikatSubkegiatan) - 3) . " duplikat lainnya");
            }
        } else {
            $this->info('Tidak ada duplikat.');
        }
        
        $this->newLine();
        
        $this->warn('Duplikat di Subkegiatan2025:');
        if (count($duplikatSubkegiatan2025) > 0) {
            $this->error("Total: " . count($duplikatSubkegiatan2025) . " record duplikat");
            
            // Tampilkan contoh duplikat (maksimal 3)
            $shown = 0;
            foreach ($duplikatSubkegiatan2025 as $index => $item) {
                if ($shown >= 3) break;
                $this->line(str_repeat('-', 80));
                $this->line("Duplikat #" . ($shown + 1) . ":");
                $this->line("  Kode: {$item['kode']}");
                $this->line("  Nama: {$item['nama']}");
                $shown++;
            }
            
            if (count($duplikatSubkegiatan2025) > 3) {
                $this->line("  ... dan " . (count($duplikatSubkegiatan2025) - 3) . " duplikat lainnya");
            }
        } else {
            $this->info('Tidak ada duplikat.');
        }

        $this->newLine();
        $this->info('========================================');
        $this->info('RINGKASAN');
        $this->info('========================================');
        $this->info('Data unik di Subkegiatan: ' . count($diffInSubkegiatan));
        $this->info('Data unik di Subkegiatan2025: ' . count($diffInSubkegiatan2025));
        $this->warn('Duplikat di Subkegiatan: ' . count($duplikatSubkegiatan) . ' record');
        $this->warn('Duplikat di Subkegiatan2025: ' . count($duplikatSubkegiatan2025) . ' record');
        $this->info('========================================');
    }

    /**
     * Hapus data yang ditemukan di Subkegiatan berdasarkan perbedaan
     *
     * @param array $diffData
     * @return void
     */
    private function deleteDiffData($diffData)
    {
        if (count($diffData) === 0) {
            $this->info('Tidak ada data yang perlu dihapus.');
            return;
        }

        $this->newLine();
        $this->warn('========================================');
        $this->warn('MENGHAPUS DATA');
        $this->warn('========================================');
        $this->newLine();

        $this->warn('Jumlah data yang akan dihapus: ' . count($diffData));
        $this->newLine();

        // Konfirmasi penghapusan
        if (!$this->confirm('Apakah Anda yakin ingin menghapus ' . count($diffData) . ' data dari Subkegiatan?', false)) {
            $this->info('Penghapusan dibatalkan.');
            return;
        }

        try {
            // Ambil ID dari data yang akan dihapus
            $idsToDelete = [];
            foreach ($diffData as $item) {
                if (isset($item['id'])) {
                    $idsToDelete[] = $item['id'];
                }
            }

            if (count($idsToDelete) === 0) {
                $this->error('Tidak ada ID valid yang ditemukan untuk dihapus.');
                return;
            }

            $this->info('Menghapus ' . count($idsToDelete) . ' record dari database...');
            $this->newLine();

            // Hapus data menggunakan whereIn untuk efisiensi
            $deleted = SubKegiatan::whereIn('id', $idsToDelete)->delete();

            $this->info('========================================');
            $this->info('PENGHAPUSAN BERHASIL');
            $this->info('========================================');
            $this->info('Total data yang dihapus: ' . $deleted);
            $this->info('========================================');
            $this->newLine();

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
