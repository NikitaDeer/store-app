<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $guarded = [];

    // Одна страна -> Много производителей
    public function manufacturers(): HasMany
    {
        return $this->hasMany(Manufacturer::class);
    }
}
