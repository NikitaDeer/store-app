<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specification extends Model
{
    protected $guarded = [];

    // Характеристика относится к Товару
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
