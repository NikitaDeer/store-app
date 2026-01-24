<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;

// --- ЯВНЫЕ ИМПОРТЫ ТАБЛИЦ ---
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use BackedEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Товары';

    public static function schema(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Секция 1: Основное
                Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название изделия')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Категория')
                            ->required(),

                        Forms\Components\Select::make('manufacturer_id')
                            ->relationship('manufacturer', 'name')
                            ->label('Производитель')
                            ->required(),
                    ])->columns(2),

                // Секция 2: Цены и Даты
                Forms\Components\Section::make('Параметры и Цены')
                    ->schema([
                        Forms\Components\DatePicker::make('manufacture_date')
                            ->label('Дата изготовления')
                            ->required(),
                        Forms\Components\TextInput::make('warranty_months')
                            ->label('Гарантия (мес)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('price_rub')
                            ->label('Цена (Руб)')
                            ->numeric()
                            ->prefix('₽')
                            ->required(),
                        Forms\Components\TextInput::make('price_usd')
                            ->label('Цена (USD)')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('discount_percent')
                            ->label('Скидка %')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('discount_reason')
                            ->label('Причина скидки')
                            ->maxLength(255),
                    ])->columns(3),

                // Секция 3: Характеристики
                Forms\Components\Section::make('Технические параметры')
                    ->schema([
                        Forms\Components\Repeater::make('specifications')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('parameter_name')
                                    ->label('Параметр')
                                    ->placeholder('Например: Вес')
                                    ->required(),
                                Forms\Components\TextInput::make('parameter_value')
                                    ->label('Значение')
                                    ->placeholder('Например: 10 кг')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Добавить параметр'),
                    ]),
                    // Секция 4: Остатки
                Forms\Components\Section::make('Наличие на складах')
                    ->schema([
                        Forms\Components\Repeater::make('stocks')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('storage_location_id')
                                    ->relationship('storageLocation', 'name')
                                    ->label('Склад / Магазин')
                                    ->required()
                                    ->distinct(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Количество')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Добавить место хранения'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Товар')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->sortable(),
                Tables\Columns\TextColumn::make('manufacturer.name')
                    ->label('Бренд')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_rub')
                    ->label('Цена')
                    ->money('rub')
                    ->sortable(),
                Tables\Columns\TextColumn::make('manufacture_date')
                    ->label('Дата изг.')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Категория'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
