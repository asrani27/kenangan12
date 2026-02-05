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
}
