<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database with categories.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Холодильники'],
            ['name' => 'Стиральные машины'],
            ['name' => 'Плиты и варочные панели'],
            ['name' => 'Духовые шкафы'],
            ['name' => 'Микроволновые печи'],
            ['name' => 'Посудомоечные машины'],
            ['name' => 'Пылесосы'],
            ['name' => 'Кондиционеры'],
            ['name' => 'Вытяжки'],
            ['name' => 'Мелкая кухонная техника'],
        ];

        Category::query()->insert($categories);
    }
}
