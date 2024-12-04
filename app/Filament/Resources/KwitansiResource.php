<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KwitansiResource\Pages;
use App\Models\Kwitansi;
use App\Models\TipeGas;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class KwitansiResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
    protected static ?string $navigationBadgeTooltip = 'Jumlah Kwitansi';
    protected static ?string $model = Kwitansi::class;
    protected static ?string $label = 'Kwitansi';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Isi Data Perusahaan')
                        ->schema([
                            Select::make('perusahaan_id')
                                ->label('perusahaan')
                                ->placeholder('Pilih Perusahaan')
                                ->preload()
                                ->native(false)
                                ->searchable()
                                ->relationship('perusahaan', 'nama')
                                ->required(),

                            TextInput::make('nomor')
                                ->label('Nomor Kwitansi')
                                ->placeholder('Masukkan Nomor')
                                ->hidden()
                                ->dehydratedWhenHidden(),

                            Grid::make(2)
                                ->schema([
                                    DatePicker::make('tanggal')
                                        ->label('Tanggal')
                                        ->placeholder('Pilih Tanggal')
                                        ->native(false)
                                        ->required(),

                                    DatePicker::make('masa')
                                        ->label('Masa Tanggang')
                                        ->placeholder('Pilih Masa Tenggang')
                                        ->native(false)
                                        ->required(),
                                ])

                        ]),
                    Step::make('Isi Data Gas')
                        ->schema([
                            Select::make('follow_up_id')
                                ->label('Status Follow Up')
                                ->placeholder('Pilih Status Follow Up')
                                ->relationship('followUp', 'nama')
                                ->preload()
                                ->searchable()
                                ->native(false)
                                ->required()
                                ->columnSpanFull()
                                ->hidden(Auth::user()->hasRole('Staff'))
                                ->reactive(), // Aktifkan reaktivitas untuk memengaruhi field lain

                            Textarea::make('catatan')
                                ->label('Catatan')
                                ->placeholder('Masukkan catatan...')
                                ->required()
                                ->columnSpanFull()
                                ->visible(fn(callable $get) => in_array($get('follow_up_id'), ['1', '2'])), // Tampilkan jika FollowUp 1 atau 2
                            Repeater::make('kwitansi')
                                ->label('Data Uraian Gas')
                                ->relationship('uraianGas')
                                ->schema([
                                    Select::make('tipe_gas_id')
                                        ->label('Pilih Tipe Gas')
                                        ->placeholder('Pilih Tipe')
                                        ->options(TipeGas::pluck('nama', 'id'))
                                        ->preload()
                                        ->native(false)
                                        ->searchable()
                                        ->required(),

                                    TextInput::make('kuantitas')
                                        ->label('Jumlah Gas')
                                        ->placeholder('Masukkan Jumlah Gas')
                                        ->numeric()
                                        ->required(),

                                    TextInput::make('harga')
                                        ->label('Harga Satuan Gas')
                                        ->placeholder('Masukkan Harga Gas')
                                        ->numeric()
                                        ->mask(RawJs::make('$money($input)'))
                                        ->stripCharacters(',')
                                        ->prefix('Rp')
                                        ->suffix('.00')
                                        ->columnSpanFull()
                                        ->required(),
                                ])
                                ->columns(2),
                        ]),
                    TextInput::make('total')
                        ->label('Harga Satuan Gas')
                        ->placeholder('Masukkan Harga Gas')
                        ->numeric()
                        ->hidden()
                        ->dehydratedWhenHidden()
                        ->nullable(),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                    ->label('Nomor Kwitansi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('perusahaan.nama')
                    ->label('Nama Perusahaan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('masa')
                    ->label('Masa Tenggang')
                    ->date()
                    ->sortable(),

                Auth::user()->hasRole('Staff')
                    ? Tables\Columns\TextColumn::make('followUp.id')
                    ->label('Status Follow Up')
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->followUp?->nama)
                    ->color(fn($state) => match ((int)$state) {
                        1 => 'warning',
                        2 => 'warning',
                        3 => 'success',
                        default => 'secondary',
                    })
                    : Tables\Columns\SelectColumn::make('follow_up_id')
                    ->label('Status Follow Up')
                    ->options(fn() => \App\Models\FollowUp::pluck('nama', 'id')->toArray()),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total Harga')
                    ->prefix('Rp')
                    ->suffix('.00')
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
                Action::make('Cetak')
                    ->label('Cetak')
                    ->button()
                    ->url(fn(Kwitansi $record): string => route('kwitansi.cetak', ['id' => $record->id]))
                    ->icon('heroicon-o-document-text')
                    ->openUrlInNewTab()
                    ->visible(fn(Kwitansi $record): bool => !is_null($record->follow_up_id)),

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
            'index' => Pages\ManageKwitansis::route('/'),
        ];
    }
}
