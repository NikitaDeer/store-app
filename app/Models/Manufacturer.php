<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $guarded = [];

    // Производитель принадлежит Стране
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    // Один производитель -> Много товаров
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
