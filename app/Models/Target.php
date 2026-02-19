<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $table = 'target_uraian';
    protected $guarded = [
        'id'
    ];

    // Accessors to map target_ attributes to target_ columns
    public function getTargetJanuariAttribute($value)
    {
        return $this->attributes['target_januari'] ?? 0;
    }

    public function getTargetFebruariAttribute($value)
    {
        return $this->attributes['target_februari'] ?? 0;
    }

    public function getTargetMaretAttribute($value)
    {
        return $this->attributes['target_maret'] ?? 0;
    }

    public function getTargetAprilAttribute($value)
    {
        return $this->attributes['target_april'] ?? 0;
    }

    public function getTargetMeiAttribute($value)
    {
        return $this->attributes['target_mei'] ?? 0;
    }

    public function getTargetJuniAttribute($value)
    {
        return $this->attributes['target_juni'] ?? 0;
    }

    public function getTargetJuliAttribute($value)
    {
        return $this->attributes['target_juli'] ?? 0;
    }

    public function getTargetAgustusAttribute($value)
    {
        return $this->attributes['target_agustus'] ?? 0;
    }

    public function getTargetSeptemberAttribute($value)
    {
        return $this->attributes['target_september'] ?? 0;
    }

    public function getTargetOktoberAttribute($value)
    {
        return $this->attributes['target_oktober'] ?? 0;
    }

    public function getTargetNovemberAttribute($value)
    {
        return $this->attributes['target_november'] ?? 0;
    }

    public function getTargetDesemberAttribute($value)
    {
        return $this->attributes['target_desember'] ?? 0;
    }

    // Mutators to map target_ attributes to target_ columns
    public function setTargetJanuariAttribute($value)
    {
        $this->attributes['target_januari'] = $value ?? 0;
    }

    public function setTargetFebruariAttribute($value)
    {
        $this->attributes['target_februari'] = $value ?? 0;
    }

    public function setTargetMaretAttribute($value)
    {
        $this->attributes['target_maret'] = $value ?? 0;
    }

    public function setTargetAprilAttribute($value)
    {
        $this->attributes['target_april'] = $value ?? 0;
    }

    public function setTargetMeiAttribute($value)
    {
        $this->attributes['target_mei'] = $value ?? 0;
    }

    public function setTargetJuniAttribute($value)
    {
        $this->attributes['target_juni'] = $value ?? 0;
    }

    public function setTargetJuliAttribute($value)
    {
        $this->attributes['target_juli'] = $value ?? 0;
    }

    public function setTargetAgustusAttribute($value)
    {
        $this->attributes['target_agustus'] = $value ?? 0;
    }

    public function setTargetSeptemberAttribute($value)
    {
        $this->attributes['target_september'] = $value ?? 0;
    }

    public function setTargetOktoberAttribute($value)
    {
        $this->attributes['target_oktober'] = $value ?? 0;
    }

    public function setTargetNovemberAttribute($value)
    {
        $this->attributes['target_november'] = $value ?? 0;
    }

    public function setTargetDesemberAttribute($value)
    {
        $this->attributes['target_desember'] = $value ?? 0;
    }

    // Accessors for realisasi fields
    public function getRealisasiJanuariAttribute($value)
    {
        return $this->attributes['realisasi_januari'] ?? 0;
    }

    public function getRealisasiFebruariAttribute($value)
    {
        return $this->attributes['realisasi_februari'] ?? 0;
    }

    public function getRealisasiMaretAttribute($value)
    {
        return $this->attributes['realisasi_maret'] ?? 0;
    }

    public function getRealisasiAprilAttribute($value)
    {
        return $this->attributes['realisasi_april'] ?? 0;
    }

    public function getRealisasiMeiAttribute($value)
    {
        return $this->attributes['realisasi_mei'] ?? 0;
    }

    public function getRealisasiJuniAttribute($value)
    {
        return $this->attributes['realisasi_juni'] ?? 0;
    }

    public function getRealisasiJuliAttribute($value)
    {
        return $this->attributes['realisasi_juli'] ?? 0;
    }

    public function getRealisasiAgustusAttribute($value)
    {
        return $this->attributes['realisasi_agustus'] ?? 0;
    }

    public function getRealisasiSeptemberAttribute($value)
    {
        return $this->attributes['realisasi_september'] ?? 0;
    }

    public function getRealisasiOktoberAttribute($value)
    {
        return $this->attributes['realisasi_oktober'] ?? 0;
    }

    public function getRealisasiNovemberAttribute($value)
    {
        return $this->attributes['realisasi_november'] ?? 0;
    }

    public function getRealisasiDesemberAttribute($value)
    {
        return $this->attributes['realisasi_desember'] ?? 0;
    }

    // Mutators for realisasi fields
    public function setRealisasiJanuariAttribute($value)
    {
        $this->attributes['realisasi_januari'] = $value ?? 0;
    }

    public function setRealisasiFebruariAttribute($value)
    {
        $this->attributes['realisasi_februari'] = $value ?? 0;
    }

    public function setRealisasiMaretAttribute($value)
    {
        $this->attributes['realisasi_maret'] = $value ?? 0;
    }

    public function setRealisasiAprilAttribute($value)
    {
        $this->attributes['realisasi_april'] = $value ?? 0;
    }

    public function setRealisasiMeiAttribute($value)
    {
        $this->attributes['realisasi_mei'] = $value ?? 0;
    }

    public function setRealisasiJuniAttribute($value)
    {
        $this->attributes['realisasi_juni'] = $value ?? 0;
    }

    public function setRealisasiJuliAttribute($value)
    {
        $this->attributes['realisasi_juli'] = $value ?? 0;
    }

    public function setRealisasiAgustusAttribute($value)
    {
        $this->attributes['realisasi_agustus'] = $value ?? 0;
    }

    public function setRealisasiSeptemberAttribute($value)
    {
        $this->attributes['realisasi_september'] = $value ?? 0;
    }

    public function setRealisasiOktoberAttribute($value)
    {
        $this->attributes['realisasi_oktober'] = $value ?? 0;
    }

    public function setRealisasiNovemberAttribute($value)
    {
        $this->attributes['realisasi_november'] = $value ?? 0;
    }

    public function setRealisasiDesemberAttribute($value)
    {
        $this->attributes['realisasi_desember'] = $value ?? 0;
    }

    public function uraian()
    {
        return $this->belongsTo(Uraian::class, 'uraian_id');
    }
}
