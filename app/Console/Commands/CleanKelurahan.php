<?php

namespace App\Console\Commands;

use App\Models\Kelurahan;
use Illuminate\Console\Command;

class CleanKelurahan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kelurahan:clean {--dry-run : Hanya tampilkan data yang akan diubah tanpa mengupdate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan character \\r dari field nama, kode_skpd, dan kode_subunit pada tabel kelurahan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('Memulai pembersihan data kelurahan...');
        $this->info('========================================');
        $this->newLine();

        try {
            // Cari semua data yang memiliki carriage return (\r) di nama
            $this->info('Mencari data dengan karakter carriage return (\\r)...');
            
            // Gunakan raw query untuk mencari carriage return di setiap field
            $dataWithSlashRInNama = Kelurahan::whereRaw('nama LIKE ?', ['%' . chr(13) . '%'])->get();
            $dataWithSlashRInKodeSkpd = Kelurahan::whereRaw('kode_skpd LIKE ?', ['%' . chr(13) . '%'])->get();
            $dataWithSlashRInKodeSubunit = Kelurahan::whereRaw('kode_subunit LIKE ?', ['%' . chr(13) . '%'])->get();
            
            // Gabungkan semua data yang perlu dibersihkan
            $allDataWithSlashR = $dataWithSlashRInNama->merge($dataWithSlashRInKodeSkpd)
                                                          ->merge($dataWithSlashRInKodeSubunit)
                                                          ->unique('id');
            
            $totalFound = $allDataWithSlashR->count();
            $this->info('Total data ditemukan dengan karakter carriage return: ' . $totalFound);
            $this->newLine();

            if ($totalFound === 0) {
                $this->info('Tidak ada data dengan karakter carriage return ditemukan.');
                $this->info('Semua data kelurahan sudah bersih.');
                return Command::SUCCESS;
            }

            // Tampilkan data yang akan diubah
            $this->info('Contoh data yang akan diubah:');
            $this->table(
                ['ID', 'Nama', 'Nama (After)', 'Kode SKPD', 'Kode SKPD (After)', 'Kode Subunit', 'Kode Subunit (After)'],
                $allDataWithSlashR->take(20)->map(function ($item) {
                    return [
                        $item->id,
                        $item->nama ?? '-',
                        $this->cleanField($item->nama),
                        $item->kode_skpd ?? '-',
                        $this->cleanField($item->kode_skpd),
                        $item->kode_subunit ?? '-',
                        $this->cleanField($item->kode_subunit),
                    ];
                })->toArray()
            );

            if ($totalFound > 20) {
                $this->info('... dan ' . ($totalFound - 20) . ' data lainnya');
            }

            $this->newLine();

            // Jika dry-run, hanya tampilkan data
            if ($this->option('dry-run')) {
                $this->info('========================================');
                $this->info('DRY RUN SELESAI');
                $this->info('Tidak ada perubahan yang dilakukan.');
                $this->info('Gunakan tanpa opsi --dry-run untuk melakukan perubahan.');
                $this->info('========================================');
                return Command::SUCCESS;
            }

            // Konfirmasi update
            if (!$this->confirm('Apakah Anda yakin ingin menghapus karakter \\r dari ' . $totalFound . ' data?', false)) {
                $this->info('Perubahan dibatalkan.');
                return Command::SUCCESS;
            }

            // Update data
            $this->info('Mengupdate data...');
            $this->newLine();

            $updated = 0;
            $progressBar = $this->output->createProgressBar($totalFound);
            $progressBar->start();

            foreach ($allDataWithSlashR as $kelurahan) {
                $needsUpdate = false;
                
                // Bersihkan field nama - gunakan getRawOriginal untuk menghindari accessor
                $rawNama = $kelurahan->getRawOriginal('nama');
                if (strpos($rawNama, chr(13)) !== false) {
                    $kelurahan->nama = $this->cleanField($rawNama);
                    $needsUpdate = true;
                }
                
                // Bersihkan field kode_skpd
                $rawKodeSkpd = $kelurahan->getRawOriginal('kode_skpd');
                if ($rawKodeSkpd && strpos($rawKodeSkpd, chr(13)) !== false) {
                    $kelurahan->kode_skpd = $this->cleanField($rawKodeSkpd);
                    $needsUpdate = true;
                }
                
                // Bersihkan field kode_subunit
                $rawKodeSubunit = $kelurahan->getRawOriginal('kode_subunit');
                if ($rawKodeSubunit && strpos($rawKodeSubunit, chr(13)) !== false) {
                    $kelurahan->kode_subunit = $this->cleanField($rawKodeSubunit);
                    $needsUpdate = true;
                }
                
                // Update jika ada perubahan
                if ($needsUpdate) {
                    $kelurahan->save();
                    $updated++;
                }
                
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
            $this->newLine();

            $this->info('========================================');
            $this->info('PEMBERSIHAN BERHASIL');
            $this->info('========================================');
            $this->info('Total data yang diupdate: ' . $updated);
            $this->info('========================================');
            $this->newLine();

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Bersihkan field dengan menghapus karakter carriage return (\r) dan newline (\n)
     *
     * @param string $field
     * @return string
     */
    private function cleanField($field)
    {
        // Hapus carriage return (\r) dan newline (\n) dari string
        return trim($field, "\r\n");
    }
}
