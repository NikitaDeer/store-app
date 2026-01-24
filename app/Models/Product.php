<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    // Касты типов (чтобы даты были датами, а не строками)
    protected $casts = [
        'manufacture_date' => 'date',
    ];

    // Товар принадлежит Категории
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Товар принадлежит Производителю
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    // У товара много записей об остатках (на разных складах)
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    // У товара много технических характеристик
    public function specifications(): HasMany
    {
        return $this->hasMany(Specification::class);
    }

    // Движение товара (приход/расход/перемещение)
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
