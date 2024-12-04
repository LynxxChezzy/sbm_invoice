<?php

namespace App\Filament\Resources;


use App\Filament\Resources\PerusahaanResource\Pages;
use App\Models\Perusahaan;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
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
                                        ->placeholder('Masukkan Nomor')
                                        ->numeric()
                                        ->minLength(11)
                                        ->required(),
                                ]),
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
