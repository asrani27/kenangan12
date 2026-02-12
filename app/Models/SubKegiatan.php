<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    protected $table = 'subkegiatan';
    protected $guarded = [
        'id'
    ];

    /**
     * Get the kegiatan that owns the sub kegiatan.
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kode_kegiatan', 'kode');
    }

    /**
     * Get the uraian for the sub kegiatan.
     */
    public function uraian()
    {
        return $this->hasMany(Uraian::class, 'kode_subkegiatan', 'kode');
    }

    /**
     * Scope a query to only include subkegiatan for a specific SKPD.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kode_skpd
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySkpd($query, $kode_skpd)
    {
        return $query->whereHas('kegiatan', function ($q) use ($kode_skpd) {
            $q->whereHas('program', function ($pq) use ($kode_skpd) {
                $pq->where('kode_skpd', $kode_skpd);
            });
        });
    }

    /**
     * Scope a query to only include subkegiatan for a specific year.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $tahun
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->whereHas('kegiatan', function ($q) use ($tahun) {
            $q->whereHas('program', function ($pq) use ($tahun) {
                $pq->where('tahun', $tahun);
            });
        });
    }

    /**
     * Scope a query to only include subkegiatan for a specific SKPD and year.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kode_skpd
     * @param  string  $tahun
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySkpdAndTahun($query, $kode_skpd, $tahun)
    {
        return $query->whereHas('kegiatan', function ($q) use ($kode_skpd, $tahun) {
            $q->whereHas('program', function ($pq) use ($kode_skpd, $tahun) {
                $pq->where('kode_skpd', $kode_skpd)
                   ->where('tahun', $tahun);
            });
        });
    }

    /**
     * Scope a query to only include subkegiatan for a specific kegiatan.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kode_kegiatan
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByKegiatan($query, $kode_kegiatan)
    {
        return $query->where('kode_kegiatan', $kode_kegiatan);
    }
}
