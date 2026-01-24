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

    // Движения, где место хранения — источник
    public function outgoingInventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'source_storage_location_id');
    }

    // Движения, где место хранения — назначение
    public function incomingInventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'destination_storage_location_id');
    }
}
