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
}
