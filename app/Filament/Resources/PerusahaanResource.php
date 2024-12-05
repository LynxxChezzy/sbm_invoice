<?php

namespace App\Filament\Resources;


use App\Filament\Resources\PerusahaanResource\Pages;
use App\Models\Perusahaan;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class PerusahaanResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'primary';
    }
    protected static ?string $navigationBadgeTooltip = 'Jumlah Perusahaan';
    protected static ?string $model = Perusahaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Kelola Data';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Perusahaan')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('nama')
                                        ->label('Nama Perusahaan')
                                        ->placeholder('Masukkan Nama Perusahaan')
                                        ->required()
                                        ->maxLength(45),

                                    TextInput::make('email')
                                        ->label('Email Perusahaan')
                                        ->placeholder('Masukkan Email Perusahaan')
                                        ->email()
                                        ->required()
                                        ->maxLength(45),
                                ])
                        ]),
                    Step::make('Kontak Perusahaan')
                        ->schema([
                            Repeater::make('kontakPerusahaan')
                                ->relationship('kontakPerusahaan')
                                ->schema([
                                    TextInput::make('nama')
                                        ->label('Nama Kontak')
                                        ->placeholder('Masukkan Nama Kontak')
                                        ->minLength(3)
                                        ->required(),

                                    TextInput::make('no_hp')
                                        ->label('Nomor Handphone')
                                        ->placeholder('+62 81234567890')
                                        ->required()
                                        ->minLength(11)
                                        ->maxLength(12)
                                        ->minValue(8)
                                        ->prefix('+62')
                                        ->mask(
                                            RawJs::make(<<<'JS'
                                                $input.startsWith('+62')
                                                    ? $input.replace(/^\+62/, '')
                                                    : ($input.startsWith('62')
                                                        ? $input.replace(/^62/, '')
                                                        : ($input.startsWith('0')
                                                            ? $input.replace(/^0/, '')
                                                            : $input
                                                        )
                                                    )
                                            JS)
                                        )
                                        ->stripCharacters([' ', '-', '(', ')']) // Hapus karakter yang tidak diperlukan
                                        ->numeric()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            // Bersihkan prefix dari input
                                            $cleaned = preg_replace('/^(\+62|62|0)/', '', $state);

                                            // Pastikan input dimulai dengan angka 8
                                            if (!str_starts_with($cleaned, '8')) {
                                                $set('no_hp', null); // Atur ke null jika tidak valid
                                            } else {
                                                $set('no_hp', $cleaned); // Simpan nomor bersih tanpa prefix
                                            }
                                        }),
                                ])->columns(2),
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Perusahaan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email Perusahaan')
                    ->searchable(),
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
            'index' => Pages\ManagePerusahaans::route('/'),
        ];
    }
}
