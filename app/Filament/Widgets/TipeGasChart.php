<?php

namespace App\Filament\Widgets;

use App\Models\TipeGas;
use App\Models\UraianGas;
use Filament\Widgets\ChartWidget;

class TipeGasChart extends ChartWidget
{
    protected static ?string $heading = 'Tipe Gas';
    protected int | string | array $rowSpan = 2;
    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '275px';
    protected function getData(): array
    {
        // Mengambil data distribusi kuantitas per tipe gas
        $data = UraianGas::with('tipeGas')
            ->get()
            ->groupBy('tipe_gas_id')
            ->map(function ($group) {
                return $group->sum('kuantitas');
            });

        $labels = TipeGas::whereIn('id', $data->keys())->pluck('nama')->toArray();
        $values = $data->values()->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Distribusi Tipe Gas',
                    'data' => $values,
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                        'rgba(255, 87, 34, 0.2)',
                        'rgba(123, 31, 162, 0.2)',
                        'rgba(0, 188, 212, 0.2)',
                        'rgba(0, 150, 136, 0.2)',
                        'rgba(76, 175, 80, 0.2)',
                        'rgba(139, 195, 74, 0.2)',
                        'rgba(255, 235, 59, 0.2)',
                        'rgba(255, 193, 7, 0.2)',
                        'rgba(96, 125, 139, 0.2)',
                        'rgba(233, 30, 99, 0.2)',
                        'rgba(244, 67, 54, 0.2)',
                        'rgba(63, 81, 181, 0.2)',
                        'rgba(33, 150, 243, 0.2)',
                    ],
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
