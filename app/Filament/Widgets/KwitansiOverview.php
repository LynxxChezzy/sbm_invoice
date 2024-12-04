<?php

namespace App\Filament\Widgets;

use App\Models\Kwitansi;
use App\Models\Perusahaan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KwitansiOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        // Hitung total kwitansi
        $totalKwitansi = Kwitansi::count();

        // Hitung total nilai transaksi
        $totalNilaiTransaksi = Kwitansi::sum('total');

        // Hitung rata-rata nilai transaksi
        $rataRataNilai = Kwitansi::average('total');

        // Hitung jumlah perusahaan
        $totalPerusahaan = Perusahaan::count();

        // Dummy data untuk grafik (contoh tren statistik)
        $kwitansiChart = Kwitansi::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->take(7)
            ->pluck('count')
            ->toArray();

        $nilaiTransaksiChart = Kwitansi::selectRaw('DATE(created_at) as date, SUM(total) as sum')
            ->groupBy('date')
            ->orderBy('date')
            ->take(7)
            ->pluck('sum')
            ->toArray();

        $rataRataChart = array_map(function ($value) {
            return round($value / 1000, 2); // Data normalisasi untuk grafik
        }, $nilaiTransaksiChart);

        return [
            Stat::make('Total Kwitansi', number_format($totalKwitansi))
                ->description('Semua kwitansi yang tercatat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart($kwitansiChart)
                ->color('primary'),

            Stat::make('Total Nilai Transaksi', 'Rp ' . number_format($totalNilaiTransaksi, 0, ',', '.'))
                ->description('Akumulasi seluruh transaksi')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($nilaiTransaksiChart)
                ->color('primary'),

            Stat::make('Total Perusahaan', number_format($totalPerusahaan))
                ->description('Perusahaan yang terdaftar')
                ->descriptionIcon('heroicon-m-building-office')
                ->chart([3, 5, 7, 9, 11, 13, 15]) // Contoh tren statis
                ->color('primary'),
        ];
    }
}
