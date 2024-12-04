<?php

namespace App\Filament\Resources\KwitansiResource\Pages;

use App\Filament\Resources\KwitansiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKwitansis extends ManageRecords
{
    protected static string $resource = KwitansiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Kwitansi'),

        ];
    }
}
