<?php

namespace App\Filament\Resources\TipeTransaksiResource\Pages;

use App\Filament\Resources\TipeTransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipeTransaksis extends ManageRecords
{
    protected static string $resource = TipeTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
