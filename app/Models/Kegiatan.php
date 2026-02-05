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
}
