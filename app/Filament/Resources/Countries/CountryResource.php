<?php

namespace App\Filament\Resources\Countries;

use App\Filament\Resources\Countries\Pages;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'Страны';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название страны')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Код (RU, DE)')
                    ->maxLength(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Страна')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('code')->label('Код'),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
