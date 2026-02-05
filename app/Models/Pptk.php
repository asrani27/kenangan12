<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pptk extends Model
{
    protected $table = 'pptk';
    protected $guarded = [
        'id'
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
