<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KontakPerusahaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan_id',
        'nama',
        'no_hp'
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id', 'id');
    }
}
