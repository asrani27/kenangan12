<?php

namespace App\Console\Commands;

use App\Models\Kegiatan;
use App\Models\Program;
use App\Models\SubKegiatan;
use App\Models\Uraian;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportRekeningBelanjaSekda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-rekening-belanja-sekda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data rekening belanja Sekda dari file Excel ke model Program';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = public_path('excel/rekening_belanja_sekda.xlsx');

        if (!file_exists($filePath)) {
            $this->error("File Excel tidak ditemukan di: {$filePath}");
            return Command::FAILURE;
        }

        $this->info('Memulai proses import data rekening belanja Sekda...');

        try {
            $this->info("Membaca file Excel dari: {$filePath}");

            // Check file size
            $fileSize = filesize($filePath);
            $this->info("Ukuran file: " . round($fileSize / 1024 / 1024, 2) . " MB");

            // Increase memory limit for large files
            ini_set('memory_limit', '512M');
            set_time_limit(300);

            $this->info("Loading spreadsheet...");
            $spreadsheet = IOFactory::load($filePath);
            $this->info("Spreadsheet loaded successfully");

            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            $this->info("Total baris dalam file: {$highestRow}");
            $this->info("Membaca sheet: " . $worksheet->getTitle());
            $this->newLine();

            $imported = 0;
            $skipped = 0;
            $error = 0;
            
            $importedKegiatan = 0;
            $skippedKegiatan = 0;
            $errorKegiatan = 0;
            
            $importedSubKegiatan = 0;
            $skippedSubKegiatan = 0;
            $errorSubKegiatan = 0;
            
            $importedUraian = 0;
            $skippedUraian = 0;
            $errorUraian = 0;

            // Mulai dari baris ke-2 (skip header)
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    // Mapping kolom sesuai spesifikasi:
                    // tahun = Kolom B (index 1)
                    // kode_skpd = Kolom E (index 4)
                    // kode_subunit = Kolom G (index 6)
                    // kode = Kolom K (index 10)
                    // nama = Kolom L (index 11)

                    $tahun = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $kodeSkpd = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $kode = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $nama = $worksheet->getCellByColumnAndRow(12, $row)->getValue();

                    // Validasi data wajib
                    if (empty($tahun) || empty($kodeSkpd) || empty($kode)) {
                        $this->warn("Baris {$row}: Data tidak lengkap, dilewati");
                        $error++;
                        continue;
                    }
                    
                    // Import ke model Program (existing logic)
                    $exists = Program::where('kode_skpd', $kodeSkpd)
                        ->where('kode', $kode)
                        ->exists();

                    if ($exists) {
                        $skipped++;
                    } else {
                        Program::create([
                            'tahun' => $tahun,
                            'kode_skpd' => $kodeSkpd,
                            'kode' => $kode,
                            'nama' => $nama,
                        ]);

                        $imported++;
                    }
                    
                    // Import ke model Kegiatan
                    // Mapping kolom sesuai spesifikasi:
                    // tahun = Kolom B (index 1)
                    // kode_skpd = Kolom E (index 4)
                    // kode_program = Kolom K (index 10)
                    // kode = Kolom M (index 12)
                    // nama = Kolom N (index 13)
                    
                    $kodeProgram = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $kodeKegiatan = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $namaKegiatan = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    
                    // Cek apakah data Kegiatan sudah ada
                    $existsKegiatan = Kegiatan::where('tahun', $tahun)
                        ->where('kode_skpd', $kodeSkpd)
                        ->where('kode_program', $kodeProgram)
                        ->where('kode', $kodeKegiatan)
                        ->exists();

                    if ($existsKegiatan) {
                        $skippedKegiatan++;
                    } else {
                        // Validasi data wajib untuk Kegiatan
                        if (!empty($tahun) && !empty($kodeSkpd) && !empty($kodeProgram) && !empty($kodeKegiatan)) {
                            Kegiatan::create([
                                'tahun' => $tahun,
                                'kode_skpd' => $kodeSkpd,
                                'kode_program' => $kodeProgram,
                                'kode' => $kodeKegiatan,
                                'nama' => $namaKegiatan,
                            ]);

                            $importedKegiatan++;
                        } else {
                            $this->warn("Baris {$row}: Data Kegiatan tidak lengkap, dilewati");
                            $errorKegiatan++;
                        }
                    }
                    
                    // Import ke model SubKegiatan
                    // Mapping kolom sesuai spesifikasi:
                    // tahun = Kolom B (index 1)
                    // kode_skpd = Kolom E (index 4)
                    // kode_program = Kolom K (index 10)
                    // kode_kegiatan = Kolom M (index 12)
                    // kode = Kolom O (index 14)
                    // nama = Kolom P (index 15)
                    
                    $kodeProgramSub = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $kodeKegiatanSub = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $kodeSubKegiatan = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $namaSubKegiatan = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    
                    // Cek apakah data SubKegiatan sudah ada
                    $existsSubKegiatan = SubKegiatan::where('tahun', $tahun)
                        ->where('kode_skpd', $kodeSkpd)
                        ->where('kode_program', $kodeProgramSub)
                        ->where('kode_kegiatan', $kodeKegiatanSub)
                        ->where('kode', $kodeSubKegiatan)
                        ->exists();

                    if ($existsSubKegiatan) {
                        $skippedSubKegiatan++;
                    } else {
                        // Validasi data wajib untuk SubKegiatan
                        if (!empty($tahun) && !empty($kodeSkpd) && !empty($kodeProgramSub) && !empty($kodeKegiatanSub) && !empty($kodeSubKegiatan)) {
                            SubKegiatan::create([
                                'tahun' => $tahun,
                                'kode_skpd' => $kodeSkpd,
                                'kode_program' => $kodeProgramSub,
                                'kode_kegiatan' => $kodeKegiatanSub,
                                'kode' => $kodeSubKegiatan,
                                'nama' => $namaSubKegiatan,
                            ]);

                            $importedSubKegiatan++;
                        } else {
                            $this->warn("Baris {$row}: Data SubKegiatan tidak lengkap, dilewati");
                            $errorSubKegiatan++;
                        }
                    }
                    
                    // Import ke model Uraian
                    // Mapping kolom sesuai spesifikasi:
                    // tahun = Kolom B (index 1)
                    // kode_skpd = Kolom E (index 4)
                    // kode_program = Kolom K (index 10)
                    // kode_kegiatan = Kolom M (index 12)
                    // kode_subkegiatan = Kolom O (index 14)
                    // kode_rekening = Kolom S (index 18)
                    // nama = Kolom T (index 19)
                    // dpa = Kolom U (index 20)
                    
                    $kodeProgramUraian = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $kodeKegiatanUraian = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $kodeSubkegiatanUraian = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $kodeRekening = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $namaUraian = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $dpa = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    
                    // Skip jika kode_rekening NULL
                    if (empty($kodeRekening)) {
                        $skippedUraian++;
                    } else {
                        // Cek apakah data Uraian sudah ada
                        $existsUraian = Uraian::where('tahun', $tahun)
                            ->where('kode_skpd', $kodeSkpd)
                            ->where('kode_program', $kodeProgramUraian)
                            ->where('kode_kegiatan', $kodeKegiatanUraian)
                            ->where('kode_subkegiatan', $kodeSubkegiatanUraian)
                            ->where('kode_rekening', $kodeRekening)
                            ->exists();

                        if ($existsUraian) {
                            $skippedUraian++;
                        } else {
                            // Validasi data wajib untuk Uraian
                            if (!empty($tahun) && !empty($kodeSkpd) && !empty($kodeProgramUraian) && !empty($kodeKegiatanUraian) && !empty($kodeSubkegiatanUraian) && !empty($kodeRekening)) {
                                Uraian::create([
                                    'tahun' => $tahun,
                                    'kode_skpd' => $kodeSkpd,
                                    'kode_program' => $kodeProgramUraian,
                                    'kode_kegiatan' => $kodeKegiatanUraian,
                                    'kode_subkegiatan' => $kodeSubkegiatanUraian,
                                    'kode_rekening' => $kodeRekening,
                                    'nama' => $namaUraian,
                                    'dpa' => $dpa,
                                ]);

                                $importedUraian++;
                            } else {
                                $this->warn("Baris {$row}: Data Uraian tidak lengkap, dilewati");
                                $errorUraian++;
                            }
                        }
                    }

                    // Progress bar - tampilkan setiap 1000 baris
                    if ($row % 1000 === 0) {
                        $this->line("Progress: {$row} baris diproses...");
                    }
                } catch (\Exception $e) {
                    $this->error("Baris {$row}: Error - " . $e->getMessage());
                    $error++;
                }
            }

            $this->newLine();
            $this->info('========================================');
            $this->info('Proses import selesai!');
            $this->newLine();
            $this->info('Model Program:');
            $this->info("Data berhasil diimport: {$imported}");
            $this->info("Data dilewati (sudah ada): {$skipped}");
            $this->info("Data gagal diimport: {$error}");
            $this->newLine();
            $this->info('Model Kegiatan:');
            $this->info("Data berhasil diimport: {$importedKegiatan}");
            $this->info("Data dilewati (sudah ada): {$skippedKegiatan}");
            $this->info("Data gagal diimport: {$errorKegiatan}");
            $this->newLine();
            $this->info('Model SubKegiatan:');
            $this->info("Data berhasil diimport: {$importedSubKegiatan}");
            $this->info("Data dilewati (sudah ada): {$skippedSubKegiatan}");
            $this->info("Data gagal diimport: {$errorSubKegiatan}");
            $this->newLine();
            $this->info('Model Uraian:');
            $this->info("Data berhasil diimport: {$importedUraian}");
            $this->info("Data dilewati (sudah ada/kode_rekening NULL): {$skippedUraian}");
            $this->info("Data gagal diimport: {$errorUraian}");
            $this->info('========================================');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Terjadi kesalahan saat membaca file Excel: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}