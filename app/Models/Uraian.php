<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uraian extends Model
{
    protected $table = 'uraian';
    protected $guarded = [
        'id'
    ];

    /**
     * Get the targets for the uraian.
     */
    public function targets(): HasMany
    {
        return $this->hasMany(Target::class, 'uraian_id');
    }
}
