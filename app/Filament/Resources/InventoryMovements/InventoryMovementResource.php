<?php

namespace App\Filament\Resources\InventoryMovements;

use App\Filament\Resources\InventoryMovements\Pages;
use App\Models\InventoryMovement;
use App\Models\Stock;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;


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
                            ->reactive()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Количество')
                            ->numeric()
                            ->minValue(1)
                            ->rules([
                                fn (Get $get) => function (string $attribute, $value, callable $fail) use ($get): void {
                                    $type = $get('type');
                                    $productId = $get('product_id');
                                    $sourceId = $get('source_storage_location_id');

                                    if (!in_array($type, ['out', 'transfer'], true)) {
                                        return;
                                    }

                                    if (!$productId || !$sourceId || !$value) {
                                        return;
                                    }

                                    $available = Stock::query()
                                        ->where('product_id', $productId)
                                        ->where('storage_location_id', $sourceId)
                                        ->value('quantity') ?? 0;

                                    if ((int) $value > (int) $available) {
                                        $fail("Недостаточно остатка на складе. Доступно: {$available}");
                                    }
                                },
                            ])
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
                            ->preload()
                            ->required(fn (Get $get): bool => in_array($get('type'), ['out', 'transfer'], true)),
                        Forms\Components\Select::make('destination_storage_location_id')
                            ->relationship('destinationStorageLocation', 'name')
                            ->label('Куда')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get): bool => in_array($get('type'), ['in', 'transfer'], true)),
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
                Section::make('Проверка')
                    ->schema([
                        Forms\Components\Placeholder::make('validation_hint')
                            ->label('Подсказка')
                            ->content(fn (Get $get): string => match ($get('type')) {
                                'in' => 'Укажите склад назначения.',
                                'out' => 'Укажите склад-источник с достаточным остатком.',
                                'transfer' => 'Укажите склад-источник и склад назначения.',
                                'adjustment' => 'Укажите склад-источник или склад назначения.',
                                default => 'Выберите тип операции.',
                            }),
                    ])
                    ->hidden(fn (Get $get): bool => $get('type') === null),
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
            ->recordActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryMovements::route('/'),
            'create' => Pages\CreateInventoryMovement::route('/create'),
        ];
    }
}
