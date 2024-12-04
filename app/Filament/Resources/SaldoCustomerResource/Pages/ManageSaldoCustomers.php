<?php

namespace App\Filament\Resources\SaldoCustomerResource\Pages;

use App\Filament\Resources\SaldoCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSaldoCustomers extends ManageRecords
{
    protected static string $resource = SaldoCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
