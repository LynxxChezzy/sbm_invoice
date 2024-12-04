<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaldoCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'kwitansi_id',
        'tipe_transaksi_id',
        'deskripsi',
        'nilai_saldo',
    ];
    public function Kwitansi(): BelongsTo
    {
        return $this->belongsTo(Kwitansi::class, 'kwitansi_id', 'id');
    }
    public function tipeTransaksi(): BelongsTo
    {
        return $this->belongsTo(TipeTransaksi::class, 'tipe_transaksi_id', 'id');
    }
}
