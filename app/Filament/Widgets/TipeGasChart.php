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
                        '#36A2EB',
                        '#FF6384',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
