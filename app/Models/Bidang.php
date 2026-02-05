<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bidang extends Model
{
    protected $table = 'bidang';
    protected $guarded = [
        'id'
    ];

    /**
     * Get the SKPD that owns the bidang.
     */
    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
