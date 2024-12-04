<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaldoCustomerResource\Pages;
use App\Filament\Resources\SaldoCustomerResource\RelationManagers;
use App\Models\Kwitansi;
use App\Models\SaldoCustomer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaldoCustomerResource extends Resource
{

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
    protected static ?string $navigationBadgeTooltip = 'Jumlah Saldo Customer';

    protected static ?string $model = SaldoCustomer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kwitansi_id')
                    ->label('Nomor Kwitansi')
                    ->placeholder('Pilih Nomor Kwitansi')
                    ->options(function () {
                        return Kwitansi::with('perusahaan')
                            ->pluck('nomor', 'id')
                            ->mapWithKeys(function ($item, $key) {
                                $kwitansi = Kwitansi::find($key);
                                return [$key => $kwitansi->nomor . ' - ' . $kwitansi->perusahaan->nama];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('tipe_transaksi_id')
                    ->label('Tipe Transaksi')
                    ->placeholder('Pilih Trasaksasi')
                    ->relationship('tipeTransaksi', 'nama')
                    ->native(false)
                    ->preload()
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('deskripsi')
                    ->label('Deskripsi')
                    ->placeholder('Masukkan Deskripsi')
                    ->minLength(10)
                    ->maxLength(50)
                    ->required(),

                Forms\Components\TextInput::make('nilai_saldo')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kwitansi.perusahaan.nama')
                    ->label('Perusahaan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kwitansi.nomor')
                    ->label('Nomor Kwitansi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kwitansi.tanggal')
                    ->label('Tanggal')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kwitansi.masa')
                    ->label('Masa Tenggang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipeTransaksi.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nilai_saldo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSaldoCustomers::route('/'),
        ];
    }
}
