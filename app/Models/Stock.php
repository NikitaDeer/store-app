<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $guarded = [];

    // Запись об остатке относится к Товару
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Запись об остатке относится к Месту хранения
    public function storageLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class);
    }
}
