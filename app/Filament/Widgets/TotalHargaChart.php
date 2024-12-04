<?php

namespace App\Filament\Widgets;

use App\Models\Kwitansi;
use Filament\Widgets\ChartWidget;

class TotalHargaChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Total Harga';
    protected int | string | array $columnSpan = '1/2';
    protected static ?string $maxHeight = '275px';



    protected function getData(): array
    {
        // Ambil data dari model Kwitansi beserta total harga yang dihitung dari uraian gas terkait
        $data = Kwitansi::with('uraianGas')
            ->get()
            ->map(function ($kwitansi) {
                // Hitung total harga per kwitansi berdasarkan uraian gas terkait
                $total = $kwitansi->uraianGas->sum(function ($uraian) {
                    return $uraian->kuantitas * $uraian->harga;
                });

                return [
                    'nomor' => $kwitansi->nomor,
                    'total' => $total,
                ];
            });

        // Siapkan data untuk chart
        $labels = $data->pluck('nomor')->toArray();
        $totals = $data->pluck('total')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Harga',
                    'data' => $totals,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
