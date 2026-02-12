<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';
    protected $guarded = [
        'id'
    ];

    /**
     * Get the kegiatan for the program.
     */
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'kode_program', 'kode');
    }

    /**
     * Get kegiatan for the program filtered by kode_skpd and tahun.
     * This filters kegiatan by checking if their parent program matches the specified criteria.
     * 
     * @param  string  $kode_skpd
     * @param  string  $tahun
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kegiatanWithFilters($kode_skpd, $tahun)
    {
        return $this->hasMany(Kegiatan::class, 'kode_program', 'kode')
            ->whereHas('program', function ($query) use ($kode_skpd, $tahun) {
                if ($kode_skpd) {
                    $query->where('kode_skpd', $kode_skpd);
                }
                if ($tahun) {
                    $query->where('tahun', $tahun);
                }
            });
    }
}
