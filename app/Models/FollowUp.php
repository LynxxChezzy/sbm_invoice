<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FollowUp extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
    ];
    public function kwitansi(): HasMany
    {
        return $this->hasMany(Kwitansi::class);
    }
}
