<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
