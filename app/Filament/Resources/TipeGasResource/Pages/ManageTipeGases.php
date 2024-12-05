<?php

namespace App\Filament\Resources\TipeGasResource\Pages;

use App\Filament\Resources\TipeGasResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipeGases extends ManageRecords
{
    protected static string $resource = TipeGasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Tipe Gas'),
        ];
    }
}
