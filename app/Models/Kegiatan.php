<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $guarded = [
        'id'
    ];

    /**
     * Get the program that owns the kegiatan.
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'kode_program', 'kode');
    }

    /**
     * Get the sub kegiatan for the kegiatan.
     */
    public function sub_kegiatan()
    {
        return $this->hasMany(SubKegiatan::class, 'kode_kegiatan', 'kode');
    }

    /**
     * Scope a query to only include kegiatan for a specific SKPD.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kode_skpd
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySkpd($query, $kode_skpd)
    {
        return $query->whereHas('program', function ($q) use ($kode_skpd) {
            $q->where('kode_skpd', $kode_skpd);
        });
    }

    /**
     * Scope a query to only include kegiatan for a specific year.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $tahun
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->whereHas('program', function ($q) use ($tahun) {
            $q->where('tahun', $tahun);
        });
    }

    /**
     * Scope a query to only include kegiatan for a specific SKPD and year.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kode_skpd
     * @param  string  $tahun
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySkpdAndTahun($query, $kode_skpd, $tahun)
    {
        return $query->whereHas('program', function ($q) use ($kode_skpd, $tahun) {
            $q->where('kode_skpd', $kode_skpd)
              ->where('tahun', $tahun);
        });
    }
}
