<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sourceStorageLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'source_storage_location_id');
    }

    public function destinationStorageLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'destination_storage_location_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
