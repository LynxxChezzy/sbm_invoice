<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 2 ? 'warning' : 'primary';
    }
    protected static ?string $navigationBadgeTooltip = 'Jumlah Tipe Gas';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Kelola Pengguna';
    protected static ?string $label = 'Pengguna';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Pengguna')
                    ->placeholder('Masukkan Nama Pengguna')
                    ->minlength(3)
                    ->maxLength(45)
                    ->columnSpanFull()
                    ->required(),

                TextInput::make('email')
                    ->label('Email Pengguna')
                    ->placeholder('Masukkan Email Pengguna')
                    ->email()
                    ->minLength(5)
                    ->maxLength(45)
                    ->required(),

                TextInput::make('password')
                    ->label('Password Pengguna')
                    ->placeholder('Masukkan Password Pengguna')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Pengguna')
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
