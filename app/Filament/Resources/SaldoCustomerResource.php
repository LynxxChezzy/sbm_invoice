<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaldoCustomerResource\Pages;
use App\Filament\Resources\SaldoCustomerResource\RelationManagers;
use App\Models\Kwitansi;
use App\Models\SaldoCustomer;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Grouping\Group;
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
        return static::getModel()::count() < 10 ? 'warning' : 'primary';
    }
    protected static ?string $navigationBadgeTooltip = 'Jumlah Saldo Customer';

    protected static ?string $model = SaldoCustomer::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

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
                    ->required()
                    ->reactive() // Jadikan reactive agar bisa memicu event
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Ambil nilai `total` dari tabel `Kwitansi` berdasarkan ID yang dipilih
                        $kwitansi = Kwitansi::find($state);
                        if ($kwitansi) {
                            $formattedTotal = number_format($kwitansi->total, 0, ',', ','); // Format sesuai input mask
                            $set('nilai_saldo', $formattedTotal); // Set nilai dengan format
                        } else {
                            $set('nilai_saldo', null);
                        }
                    }),

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
                    ->label('Nilai Saldo')
                    ->placeholder('Akan Terisi Otomatis')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->prefix('Rp')
                    ->suffix('.00')
                    ->numeric()
                    ->readOnly()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kwitansi.perusahaan.nama')
                    ->label('Customer')
                    ->formatStateUsing(function ($record) {
                        $namaPerusahaan = $record->kwitansi->perusahaan->nama ?? '-';
                        $tanggal = $record->kwitansi->tanggal
                            ? Carbon::parse($record->kwitansi->tanggal)->isoFormat('dddd, D/M/YYYY')
                            : '-';

                        return "{$namaPerusahaan}<br><span class='text-gray-400'>{$tanggal}</span>";
                    })
                    ->html()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kwitansi.nomor')
                    ->label('Nomor Kwitansi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipeTransaksi.nama')
                    ->label('Transaksi')
                    ->numeric(),

                Tables\Columns\TextColumn::make('kwitansi.masa')
                    ->label('Masa Tenggang')
                    ->date(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(15)
                    ->searchable(),

                Tables\Columns\TextColumn::make('kwitansi.total')
                    ->label('Nilai')
                    ->numeric()
                    ->money('IDR')
                    ->summarize(
                        Sum::make()
                            ->label('Total Nilai')
                            ->money('IDR')
                    ),
                Tables\Columns\TextColumn::make('nilai_saldo')
                    ->label('Nilai Saldo')
                    ->numeric()
                    ->money('IDR')
                    ->summarize(
                        Sum::make()
                            ->label('Total Nilai Saldo')
                            ->money('IDR')
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('kwitansi.perusahaan.nama')
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
