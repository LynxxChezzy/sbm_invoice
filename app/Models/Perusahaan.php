<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perusahaan extends Model
{
    protected $fillable = [
        'nama',
        'email'
    ];
    public function kontakPerusahaan(): HasMany
    {
        return $this->hasMany(KontakPerusahaan::class, 'perusahaan_id');
    }
    public function kwitansi(): HasMany
    {
        return $this->hasMany(Kwitansi::class, 'perusahaan_id');
    }
}
