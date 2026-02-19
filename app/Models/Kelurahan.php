<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelurahan extends Model
{
    protected $table = 'kelurahan';
    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    /**
     * Get the nama attribute and clean it.
     */
    public function getNamaAttribute($value)
    {
        return trim($value, "\r\n");
    }

    /**
     * Get the kode_skpd attribute and clean it.
     */
    public function getKodeSkpdAttribute($value)
    {
        return trim($value, "\r\n");
    }

    /**
     * Get the kode_subunit attribute and clean it.
     */
    public function getKodeSubunitAttribute($value)
    {
        return trim($value, "\r\n");
    }

    /**
     * Get the SKPD that owns the kelurahan.
     */
    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'kode_skpd', 'kode_skpd');
    }
}
