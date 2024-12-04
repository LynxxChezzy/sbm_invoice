<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipeGas extends Model
{
    protected $fillable = [
        'nama'
    ];
    public function kwitansi(): HasMany
    {
        return $this->hasMany(Kwitansi::class, 'perusahaan_id');
    }
}
