<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorageLocation extends Model
{
    protected $guarded = [];

    // Одно место хранения -> Много записей об остатках
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
