<?php

namespace Database\Seeders;

use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class StorageLocationSeeder extends Seeder
{
    /**
     * Seed the application's database with storage locations.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Главный склад', 'type' => 'warehouse'],
            ['name' => 'Склад №2 (Северный)', 'type' => 'warehouse'],
            ['name' => 'Магазин на Ленина, 12', 'type' => 'store'],
            ['name' => 'Магазин на Победы, 45', 'type' => 'store'],
            ['name' => 'Магазин ТЦ "Центр"', 'type' => 'store'],
        ];

        foreach ($locations as $location) {
            StorageLocation::query()->updateOrCreate(
                ['name' => $location['name']],
                ['type' => $location['type']]
            );
        }
    }
}
