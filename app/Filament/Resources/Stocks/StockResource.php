<?php

namespace App\Filament\Resources\Stocks;

use App\Filament\Resources\Stocks\Pages;
use App\Models\Stock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Остатки';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Товар')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.category.name')
                    ->label('Категория')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.manufacturer.name')
                    ->label('Производитель')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('storageLocation.name')
                    ->label('Склад/Магазин')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('storageLocation.type')
                    ->label('Тип')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'store' => 'Магазин',
                        'warehouse' => 'Склад',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'store' => 'success',
                        'warehouse' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Остаток')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.min_stock')
                    ->label('Мин. остаток')
                    ->sortable(),
                Tables\Columns\TextColumn::make('low_stock')
                    ->label('Статус')
                    ->getStateUsing(fn (Stock $record): string => $record->quantity < $record->product->min_stock ? 'Низкий' : 'OK')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Низкий' ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('storage_location_id')
                    ->relationship('storageLocation', 'name')
                    ->label('Склад/Магазин'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['product.category', 'product.manufacturer', 'storageLocation']);
    }
}
