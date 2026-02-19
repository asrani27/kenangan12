<?php

namespace App\Console\Commands;

use App\Models\Skpd;
use Illuminate\Console\Command;

class CleanKodeSkpd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-kode-skpd {--dry-run : Hanya tampilkan data yang akan diubah tanpa mengupdate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus karakter /r dari field kode_skpd di tabel skpd';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('Memulai pembersihan kode_skpd...');
        $this->info('========================================');
        $this->newLine();

        try {
            // Cari semua data yang memiliki carriage return (\r) di kode_skpd
            $this->info('Mencari data dengan karakter carriage return (\\r)...');
            
            // Gunakan raw query untuk mencari carriage return
            $dataWithSlashR = Skpd::whereRaw('kode_skpd LIKE ?', ['%' . chr(13) . '%'])->get();
            
            $totalFound = $dataWithSlashR->count();
            $this->info('Total data ditemukan: ' . $totalFound);
            $this->newLine();

            if ($totalFound === 0) {
                $this->info('Tidak ada data dengan karakter carriage return ditemukan.');
                return Command::SUCCESS;
            }

            // Tampilkan data yang akan diubah
            $this->info('Data yang akan diubah:');
            $this->table(
                ['ID', 'Nama SKPD', 'Kode SKPD (Saat Ini)', 'Kode SKPD (Setelah)'],
                $dataWithSlashR->take(20)->map(function ($item) {
                    return [
                        $item->id,
                        $item->nama ?? '-',
                        $item->kode_skpd,
                        $this->cleanKode($item->kode_skpd)
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
            if (!$this->confirm('Apakah Anda yakin ingin menghapus karakter /r dari ' . $totalFound . ' data?', false)) {
                $this->info('Perubahan dibatalkan.');
                return Command::SUCCESS;
            }

            // Update data
            $this->info('Mengupdate data...');
            $this->newLine();

            $updated = 0;
            $progressBar = $this->output->createProgressBar($totalFound);
            $progressBar->start();

            foreach ($dataWithSlashR as $skpd) {
                $originalKode = $skpd->kode_skpd;
                $cleanKode = $this->cleanKode($originalKode);
                
                $skpd->kode_skpd = $cleanKode;
                $skpd->save();
                
                $updated++;
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
     * Bersihkan kode dengan menghapus karakter carriage return (\r)
     *
     * @param string $kode
     * @return string
     */
    private function cleanKode($kode)
    {
        // Hapus carriage return (\r) dan newline (\n) dari akhir string
        return trim($kode, "\r\n");
    }
}