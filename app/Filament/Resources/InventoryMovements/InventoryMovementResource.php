<?php

namespace App\Filament\Resources\InventoryMovements;

use App\Filament\Resources\InventoryMovements\Pages;
use App\Models\InventoryMovement;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Движение товаров';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Основное')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->label('Товар')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->label('Тип операции')
                            ->options([
                                'in' => 'Приход',
                                'out' => 'Расход',
                                'transfer' => 'Перемещение',
                                'adjustment' => 'Корректировка',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Количество')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Forms\Components\DateTimePicker::make('moved_at')
                            ->label('Дата движения')
                            ->default(now()),
                    ])
                    ->columns(2),
                Section::make('Локации и ответственный')
                    ->schema([
                        Forms\Components\Select::make('source_storage_location_id')
                            ->relationship('sourceStorageLocation', 'name')
                            ->label('Откуда')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('destination_storage_location_id')
                            ->relationship('destinationStorageLocation', 'name')
                            ->label('Куда')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Пользователь')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('note')
                            ->label('Комментарий')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Товар')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in' => 'Приход',
                        'out' => 'Расход',
                        'transfer' => 'Перемещение',
                        'adjustment' => 'Корректировка',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'transfer' => 'info',
                        'adjustment' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Кол-во')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sourceStorageLocation.name')
                    ->label('Откуда')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('destinationStorageLocation.name')
                    ->label('Куда')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('moved_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип')
                    ->options([
                        'in' => 'Приход',
                        'out' => 'Расход',
                        'transfer' => 'Перемещение',
                        'adjustment' => 'Корректировка',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryMovements::route('/'),
            'create' => Pages\CreateInventoryMovement::route('/create'),
            'edit' => Pages\EditInventoryMovement::route('/{record}/edit'),
        ];
    }
}
