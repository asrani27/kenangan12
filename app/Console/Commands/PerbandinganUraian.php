<?php

namespace App\Console\Commands;

use App\Models\Uraian;
use App\Models\Uraian2026;
use Illuminate\Console\Command;

class PerbandinganUraian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:perbandingan-uraian {--delete : Hapus data yang ditemukan di Uraian} {--add : Tambahkan data dari Uraian2026 ke Uraian} {--sync : Hapus dan tambahkan data (sinkronisasi penuh)} {--clean-duplicates : Hapus data duplikat dari Uraian (pertahankan ID terbaru)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bandingkan data antara model Uraian dan Uraian2026 berdasarkan tahun, kode_skpd, kode_subunit, kode_program, kode_kegiatan, kode_subkegiatan, dan kode_rekening. Opsi tambahan: --delete (hapus data unik), --add (tambah data), --sync (hapus dan tambahkan), --clean-duplicates (hapus duplikat)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('Memulai perbandingan data Uraian...');
        $this->info('========================================');
        $this->newLine();

        try {
            // Ambil sample data untuk menampilkan field yang tersedia
            $this->info('Mengambil sample data...');
            $sampleUraian = Uraian::first();
            $sampleUraian2026 = Uraian2026::first();
            
            $this->info('========================================');
            $this->info('FIELD YANG TERSEDIA');
            $this->info('========================================');
            if ($sampleUraian) {
                $this->info('Field di Uraian: ' . implode(', ', array_keys($sampleUraian->toArray())));
            }
            if ($sampleUraian2026) {
                $this->info('Field di Uraian2026: ' . implode(', ', array_keys($sampleUraian2026->toArray())));
            }
            $this->info('========================================');
            $this->info('Field untuk perbandingan: tahun, kode_skpd, kode_subunit, kode_program, kode_kegiatan, kode_subkegiatan, kode_rekening');
            $this->info('========================================');
            $this->newLine();

            // Hitung total data
            $this->info('Menghitung total data...');
            $totalUraian = Uraian::count();
            $totalUraian2026 = Uraian2026::count();
            $this->info('Total data di Uraian: ' . $totalUraian);
            $this->info('Total data di Uraian2026: ' . $totalUraian2026);
            $this->newLine();

            // Analisis duplikat (hanya count, tidak simpan full data)
            $this->info('Menganalisis duplikat...');
            $duplikatUraian = $this->countDuplicates(Uraian::class);
            $duplikatUraian2026 = $this->countDuplicates(Uraian2026::class);
            $this->info('Duplikat di Uraian: ' . $duplikatUraian . ' record');
            $this->info('Duplikat di Uraian2026: ' . $duplikatUraian2026 . ' record');
            $this->newLine();

            // Analisis perbedaan data menggunakan chunking
            $this->info('Menganalisis perbedaan data...');
            $this->newLine();

            // Build keys dari Uraian2026 untuk perbandingan
            $this->info('Membuat indeks dari Uraian2026...');
            $keysUraian2026 = [];
            Uraian2026::chunk(1000, function($items) use (&$keysUraian2026, &$progressBar) {
                foreach ($items as $item) {
                    $key = $this->createUniqueKey($item->toArray());
                    $keysUraian2026[$key] = true;
                }
                $this->info('Processed ' . count($keysUraian2026) . ' records...');
            });
            $this->info('Total indeks Uraian2026: ' . count($keysUraian2026));
            $this->newLine();

            // Cari data yang ada di Uraian tapi tidak ada di Uraian2026
            $this->info('Mencari data unik di Uraian...');
            $diffInUraian = $this->findDifferences(Uraian::class, $keysUraian2026);
            $this->info('Data unik di Uraian: ' . count($diffInUraian));
            $this->newLine();

            // Build keys dari Uraian untuk perbandingan
            $this->info('Membuat indeks dari Uraian...');
            $keysUraian = [];
            Uraian::chunk(1000, function($items) use (&$keysUraian) {
                foreach ($items as $item) {
                    $key = $this->createUniqueKey($item->toArray());
                    $keysUraian[$key] = true;
                }
                $this->info('Processed ' . count($keysUraian) . ' records...');
            });
            $this->info('Total indeks Uraian: ' . count($keysUraian));
            $this->newLine();

            // Cari data yang ada di Uraian2026 tapi tidak ada di Uraian
            $this->info('Mencari data unik di Uraian2026...');
            $diffInUraian2026 = $this->findDifferences(Uraian2026::class, $keysUraian);
            $this->info('Data unik di Uraian2026: ' . count($diffInUraian2026));
            $this->newLine();

            // Tampilkan hasil
            $this->displayResults($diffInUraian, $diffInUraian2026, $duplikatUraian, $duplikatUraian2026);

            // Hapus data jika opsi --delete atau --sync diaktifkan
            if ($this->option('delete') || $this->option('sync')) {
                $this->deleteDiffData($diffInUraian);
            }

            // Tambah data jika opsi --add atau --sync diaktifkan
            if ($this->option('add') || $this->option('sync')) {
                $this->addDiffData($diffInUraian2026);
            }

            // Bersihkan duplikat jika opsi --clean-duplicates diaktifkan
            if ($this->option('clean-duplicates')) {
                $this->cleanDuplicates();
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Cari perbedaan menggunakan chunking untuk efisiensi memory
     *
     * @param string $modelClass
     * @param array $targetKeys
     * @return array
     */
    private function findDifferences($modelClass, $targetKeys)
    {
        $differences = [];

        $modelClass::chunk(1000, function($items) use (&$differences, $targetKeys) {
            foreach ($items as $item) {
                $key = $this->createUniqueKey($item->toArray());
                if (!isset($targetKeys[$key])) {
                    $differences[] = $item->toArray();
                }
            }
        });

        return $differences;
    }

    /**
     * Hitung jumlah duplikat dalam model
     *
     * @param string $modelClass
     * @return int
     */
    private function countDuplicates($modelClass)
    {
        $counts = [];
        $duplicateCount = 0;

        $modelClass::chunk(1000, function($items) use (&$counts, &$duplicateCount) {
            foreach ($items as $item) {
                $key = $this->createUniqueKey($item->toArray());
                if (!isset($counts[$key])) {
                    $counts[$key] = 0;
                }
                $counts[$key]++;
                
                // Jika count > 1, ini adalah duplikat tambahan
                if ($counts[$key] === 2) {
                    $duplicateCount += 2; // Tambah 2 untuk record pertama dan kedua
                } elseif ($counts[$key] > 2) {
                    $duplicateCount++; // Tambah 1 untuk setiap duplikat tambahan
                }
            }
        });

        return $duplicateCount;
    }

    /**
     * Buat kunci unik dari data untuk perbandingan
     * Berdasarkan field: tahun, kode_skpd, kode_subunit, kode_program, kode_kegiatan, kode_subkegiatan, kode_rekening
     *
     * @param array $item
     * @return string
     */
    private function createUniqueKey($item)
    {
        // Gunakan 7 field untuk membuat kunci unik sesuai permintaan
        $fields = ['tahun', 'kode_skpd', 'kode_subunit', 'kode_program', 'kode_kegiatan', 'kode_subkegiatan', 'kode_rekening'];
        
        // Cek apakah semua field tersedia
        $allFieldsExist = true;
        foreach ($fields as $field) {
            if (!isset($item[$field])) {
                $allFieldsExist = false;
                break;
            }
        }
        
        if ($allFieldsExist) {
            // Buat kunci dari kombinasi 7 field
            $keyParts = [
                $item['tahun'] ?? '',
                $item['kode_skpd'] ?? '',
                $item['kode_subunit'] ?? '',
                $item['kode_program'] ?? '',
                $item['kode_kegiatan'] ?? '',
                $item['kode_subkegiatan'] ?? '',
                $item['kode_rekening'] ?? ''
            ];
            return md5(implode('|', $keyParts));
        }
        
        // Fallback: gunakan semua field
        return md5(json_encode($item));
    }

    /**
     * Tampilkan hasil perbandingan
     *
     * @param array $diffInUraian
     * @param array $diffInUraian2026
     * @param int $duplikatUraian
     * @param int $duplikatUraian2026
     * @return void
     */
    private function displayResults($diffInUraian, $diffInUraian2026, $duplikatUraian, $duplikatUraian2026)
    {
        $this->info('========================================');
        $this->info('HASIL PERBANDINGAN');
        $this->info('========================================');
        $this->newLine();

        // Tampilkan data yang ada di Uraian tapi tidak di Uraian2026 (SELIHSH)
        $this->warn('DATA SELISIH (Ada di Uraian TAPI TIDAK ada di Uraian2026):');
        $this->info('Total: ' . count($diffInUraian) . ' record');
        $this->newLine();

        if (count($diffInUraian) > 0) {
            // Tampilkan dalam format table yang ringkas
            $this->table(
                ['No', 'ID', 'Tahun', 'SKPD', 'Subunit', 'Program', 'Kegiatan', 'Subkegiatan', 'Rekening'],
                $this->formatDiffData($diffInUraian)
            );
        } else {
            $this->info('Tidak ada data selisih.');
        }

        $this->newLine();
        $this->newLine();

        // Tampilkan data yang ada di Uraian2026 tapi tidak di Uraian
        $this->warn('Data yang ada di Uraian2026 TAPI TIDAK ada di Uraian:');
        $this->info('Total: ' . count($diffInUraian2026) . ' record');
        $this->newLine();

        if (count($diffInUraian2026) > 0) {
            // Tampilkan dalam format table yang ringkas
            $this->table(
                ['No', 'ID', 'Tahun', 'SKPD', 'Subunit', 'Program', 'Kegiatan', 'Subkegiatan', 'Rekening'],
                $this->formatDiffData($diffInUraian2026)
            );
        } else {
            $this->info('Tidak ada perbedaan.');
        }

        $this->newLine();
        $this->newLine();

        // Tampilkan informasi duplikat
        $this->warn('INFORMASI DUPLIKAT:');
        $this->newLine();
        
        $this->warn('Duplikat di Uraian:');
        if ($duplikatUraian > 0) {
            $this->error("Total: " . $duplikatUraian . " record duplikat");
        } else {
            $this->info('Tidak ada duplikat.');
        }
        
        $this->newLine();
        
        $this->warn('Duplikat di Uraian2026:');
        if ($duplikatUraian2026 > 0) {
            $this->error("Total: " . $duplikatUraian2026 . " record duplikat");
        } else {
            $this->info('Tidak ada duplikat.');
        }

        $this->newLine();
        $this->info('========================================');
        $this->info('RINGKASAN');
        $this->info('========================================');
        $this->info('Data unik di Uraian (Selisih): ' . count($diffInUraian));
        $this->info('Data unik di Uraian2026: ' . count($diffInUraian2026));
        $this->warn('Duplikat di Uraian: ' . $duplikatUraian . ' record');
        $this->warn('Duplikat di Uraian2026: ' . $duplikatUraian2026 . ' record');
        $this->info('========================================');
    }

    /**
     * Format data untuk ditampilkan dalam table
     *
     * @param array $data
     * @return array
     */
    private function formatDiffData($data)
    {
        $formatted = [];
        foreach ($data as $index => $item) {
            $formatted[] = [
                'no' => $index + 1,
                'id' => $item['id'] ?? '-',
                'tahun' => $item['tahun'] ?? '-',
                'skpd' => $item['kode_skpd'] ?? '-',
                'subunit' => $item['kode_subunit'] ?? '-',
                'program' => $item['kode_program'] ?? '-',
                'kegiatan' => $item['kode_kegiatan'] ?? '-',
                'subkegiatan' => $item['kode_subkegiatan'] ?? '-',
                'rekening' => $item['kode_rekening'] ?? '-',
            ];
            
            // Batasi maksimal 20 data untuk table
            if ($index >= 19) {
                break;
            }
        }
        return $formatted;
    }

    /**
     * Hapus data yang ditemukan di Uraian berdasarkan perbedaan
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
        if (!$this->confirm('Apakah Anda yakin ingin menghapus ' . count($diffData) . ' data dari Uraian?', false)) {
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
            $deleted = Uraian::whereIn('id', $idsToDelete)->delete();

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

    /**
     * Tambahkan data dari Uraian2026 ke Uraian berdasarkan perbedaan
     *
     * @param array $diffData
     * @return void
     */
    private function addDiffData($diffData)
    {
        if (count($diffData) === 0) {
            $this->info('Tidak ada data yang perlu ditambahkan.');
            return;
        }

        $this->newLine();
        $this->warn('========================================');
        $this->warn('MENAMBAHKAN DATA');
        $this->warn('========================================');
        $this->newLine();

        $this->warn('Jumlah data yang akan ditambahkan: ' . count($diffData));
        $this->newLine();

        // Konfirmasi penambahan
        if (!$this->confirm('Apakah Anda yakin ingin menambahkan ' . count($diffData) . ' data dari Uraian2026 ke Uraian?', false)) {
            $this->info('Penambahan dibatalkan.');
            return;
        }

        try {
            $this->info('Menambahkan ' . count($diffData) . ' record ke database...');
            $this->newLine();

            $added = 0;
            $failed = 0;

            foreach ($diffData as $item) {
                // Hapus field 'id' karena akan auto-increment
                $itemData = $item;
                unset($itemData['id']);
                unset($itemData['created_at']);
                unset($itemData['updated_at']);

                try {
                    Uraian::create($itemData);
                    $added++;
                } catch (\Exception $e) {
                    $failed++;
                    $this->error('Gagal menambahkan data: ' . $e->getMessage());
                }
            }

            $this->newLine();
            $this->info('========================================');
            $this->info('PENAMBAHAN SELESAI');
            $this->info('========================================');
            $this->info('Total data yang ditambahkan: ' . $added);
            if ($failed > 0) {
                $this->error('Total data yang gagal: ' . $failed);
            }
            $this->info('========================================');
            $this->newLine();

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat menambahkan data: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Hapus data duplikat dari Uraian (pertahankan ID terbaru)
     *
     * @return void
     */
    private function cleanDuplicates()
    {
        $this->newLine();
        $this->warn('========================================');
        $this->warn('MEMBERSIHKAN DUPLIKAT');
        $this->warn('========================================');
        $this->newLine();

        try {
            // Cari semua duplikat
            $this->info('Mencari duplikat...');
            $duplicates = [];
            $seen = [];
            
            Uraian::chunk(1000, function($items) use (&$duplicates, &$seen) {
                foreach ($items as $item) {
                    $key = $this->createUniqueKey($item->toArray());
                    
                    if (!isset($seen[$key])) {
                        $seen[$key] = [];
                    }
                    $seen[$key][] = $item->id;
                }
            });

            // Identifikasi duplikat (lebih dari 1 ID untuk key yang sama)
            foreach ($seen as $key => $ids) {
                if (count($ids) > 1) {
                    // Sort ID descending, pertahankan yang terbesar (terbaru)
                    rsort($ids);
                    // Hapus semua kecuali ID terbesar
                    array_shift($ids); // Hapus ID terbesar dari array yang akan dihapus
                    $duplicates = array_merge($duplicates, $ids);
                }
            }

            if (count($duplicates) === 0) {
                $this->info('Tidak ada duplikat yang ditemukan.');
                return;
            }

            $this->warn('Jumlah duplikat yang ditemukan: ' . count($duplicates) . ' record');
            $this->newLine();

            // Konfirmasi penghapusan
            if (!$this->confirm('Apakah Anda yakin ingin menghapus ' . count($duplicates) . ' duplikat dari Uraian?', false)) {
                $this->info('Penghapusan duplikat dibatalkan.');
                return;
            }

            $this->info('Menghapus ' . count($duplicates) . ' duplikat dari database...');
            $this->newLine();

            // Hapus duplikat dalam batch
            $deleted = 0;
            foreach (array_chunk($duplicates, 1000) as $idsChunk) {
                $deleted += Uraian::whereIn('id', $idsChunk)->delete();
            }

            $this->newLine();
            $this->info('========================================');
            $this->info('PEMBERSIHAN DUPLIKAT BERHASIL');
            $this->info('========================================');
            $this->info('Total duplikat yang dihapus: ' . $deleted);
            $this->info('========================================');
            $this->newLine();

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat membersihkan duplikat: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
