<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skpd extends Model
{
    protected $table = 'skpd';
    protected $guarded = [
        'id'
    ];

    /**
     * Get the user that created the SKPD.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the kelurahan for the SKPD.
     */
    public function kelurahan(): HasMany
    {
        return $this->hasMany(Kelurahan::class, 'kode_skpd', 'kode_skpd');
    }

    /**
     * Get the kode_subunit attribute and clean it.
     */
    public function getKodeSubunitAttribute($value)
    {
        return trim($value, "\r\n");
    }
}
