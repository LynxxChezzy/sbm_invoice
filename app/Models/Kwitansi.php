<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kwitansi extends Model
{
    protected $fillable = [
        'perusahaan_id',
        'follow_up_id',
        'nomor',
        'tanggal',
        'masa',
        'catatan',
        'total'
    ];
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id', 'id');
    }
    public function followUp(): BelongsTo
    {
        return $this->belongsTo(FollowUp::class, 'follow_up_id', 'id');
    }
    public function uraianGas(): HasMany
    {
        return $this->hasMany(UraianGas::class);
    }
    public function saldoCustomers(): HasMany
    {
        return $this->hasMany(SaldoCustomer::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kwitansi) {
            // Menentukan nomor kwitansi
            $currentYear = now()->format('y'); // Mengambil 2 digit terakhir tahun
            $lastKwitansi = self::whereYear('created_at', now()->year)
                ->latest('id')
                ->first();

            $lastNumber = $lastKwitansi ? intval(substr($lastKwitansi->nomor, 3)) : 0; // Mengambil angka terakhir dari nomor sebelumnya
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT); // Menambahkan 1 dan format menjadi 5 digit
            $kwitansi->nomor = "{$currentYear}.{$newNumber}";
        });
    }
}
