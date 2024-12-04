<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UraianGas extends Model
{
    protected $fillable = [
        'kwitansi_id',
        'tipe_gas_id',
        'kuantitas',
        'harga'
    ];

    public function kwitansi(): BelongsTo
    {
        return $this->belongsTo(Kwitansi::class);
    }
    public function tipeGas(): BelongsTo
    {
        return $this->belongsTo(TipeGas::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($uraianGas) {
            // Menghitung total dari semua uraianGas yang terkait dengan Kwitansi
            $total = $uraianGas->kwitansi->uraianGas->sum(function ($uraian) {
                return $uraian->kuantitas * $uraian->harga;
            });

            // Memperbarui total pada Kwitansi
            $uraianGas->kwitansi->update(['total' => $total]);
        });
    }
}
