<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database with products.
     */
    public function run(): void
    {
        $categoriesByName = Category::query()->pluck('id', 'name');
        $manufacturersByName = Manufacturer::query()->pluck('id', 'name');

        $storageLocations = StorageLocation::query()->get();

        $products = [
            [
                'name' => 'Холодильник Bosch KGN39VL1MR',
                'category' => 'Холодильники',
                'manufacturer' => 'Bosch',
                'manufacture_date' => '2025-05-12',
                'warranty_months' => 24,
                'price_rub' => 64990.00,
                'price_usd' => 699.00,
                'discount_percent' => 10,
                'discount_reason' => 'Акция',
                'specifications' => [
                    ['parameter_name' => 'Тип', 'parameter_value' => 'Двухкамерный'],
                    ['parameter_name' => 'Объем', 'parameter_value' => '368 л'],
                    ['parameter_name' => 'No Frost', 'parameter_value' => 'Да'],
                    ['parameter_name' => 'Класс энергопотребления', 'parameter_value' => 'A++'],
                ],
            ],
            [
                'name' => 'Стиральная машина Бирюса WM-S712',
                'category' => 'Стиральные машины',
                'manufacturer' => 'Бирюса',
                'manufacture_date' => '2025-03-20',
                'warranty_months' => 24,
                'price_rub' => 45990.00,
                'price_usd' => 499.00,
                'discount_percent' => 0,
                'discount_reason' => null,
                'specifications' => [
                    ['parameter_name' => 'Загрузка', 'parameter_value' => '8.5 кг'],
                    ['parameter_name' => 'Скорость отжима', 'parameter_value' => '1200 об/мин'],
                    ['parameter_name' => 'Инверторный мотор', 'parameter_value' => 'Да'],
                ],
            ],
            [
                'name' => 'Пылесос Whirlpool VC18M21',
                'category' => 'Пылесосы',
                'manufacturer' => 'Whirlpool',
                'manufacture_date' => '2025-02-05',
                'warranty_months' => 12,
                'price_rub' => 10990.00,
                'price_usd' => 129.00,
                'discount_percent' => 5,
                'discount_reason' => 'Витринный образец',
                'specifications' => [
                    ['parameter_name' => 'Тип', 'parameter_value' => 'С контейнером'],
                    ['parameter_name' => 'Мощность всасывания', 'parameter_value' => '380 Вт'],
                    ['parameter_name' => 'Фильтр', 'parameter_value' => 'HEPA'],
                ],
            ],
            [
                'name' => 'Посудомоечная машина Beko DIS26021',
                'category' => 'Посудомоечные машины',
                'manufacturer' => 'Beko',
                'manufacture_date' => '2024-12-10',
                'warranty_months' => 24,
                'price_rub' => 38990.00,
                'price_usd' => 429.00,
                'discount_percent' => 0,
                'discount_reason' => null,
                'specifications' => [
                    ['parameter_name' => 'Вместимость', 'parameter_value' => '10 комплектов'],
                    ['parameter_name' => 'Ширина', 'parameter_value' => '45 см'],
                    ['parameter_name' => 'Класс энергопотребления', 'parameter_value' => 'A+'],
                ],
            ],
            [
                'name' => 'Микроволновая печь Haier HMX-DG259X',
                'category' => 'Микроволновые печи',
                'manufacturer' => 'Haier',
                'manufacture_date' => '2025-01-18',
                'warranty_months' => 12,
                'price_rub' => 12990.00,
                'price_usd' => 149.00,
                'discount_percent' => 15,
                'discount_reason' => 'Акция',
                'specifications' => [
                    ['parameter_name' => 'Объем', 'parameter_value' => '25 л'],
                    ['parameter_name' => 'Гриль', 'parameter_value' => 'Да'],
                    ['parameter_name' => 'Мощность', 'parameter_value' => '900 Вт'],
                ],
            ],
            [
                'name' => 'Вытяжка Hansa OMC6551IH',
                'category' => 'Вытяжки',
                'manufacturer' => 'Hansa',
                'manufacture_date' => '2024-11-05',
                'warranty_months' => 24,
                'price_rub' => 21990.00,
                'price_usd' => 239.00,
                'discount_percent' => 0,
                'discount_reason' => null,
                'specifications' => [
                    ['parameter_name' => 'Ширина', 'parameter_value' => '60 см'],
                    ['parameter_name' => 'Производительность', 'parameter_value' => '650 м³/ч'],
                    ['parameter_name' => 'Тип управления', 'parameter_value' => 'Сенсорное'],
                ],
            ],
            [
                'name' => 'Плита электрическая Indesit IS5V4KHW',
                'category' => 'Плиты и варочные панели',
                'manufacturer' => 'Indesit',
                'manufacture_date' => '2024-10-22',
                'warranty_months' => 24,
                'price_rub' => 31990.00,
                'price_usd' => 349.00,
                'discount_percent' => 0,
                'discount_reason' => null,
                'specifications' => [
                    ['parameter_name' => 'Тип', 'parameter_value' => 'Электрическая'],
                    ['parameter_name' => 'Объем духовки', 'parameter_value' => '59 л'],
                    ['parameter_name' => 'Количество конфорок', 'parameter_value' => '4'],
                ],
            ],
            [
                'name' => 'Духовой шкаф Ariston FZ 45.1',
                'category' => 'Духовые шкафы',
                'manufacturer' => 'Ariston',
                'manufacture_date' => '2025-06-01',
                'warranty_months' => 24,
                'price_rub' => 27990.00,
                'price_usd' => 309.00,
                'discount_percent' => 7,
                'discount_reason' => 'Сезонная распродажа',
                'specifications' => [
                    ['parameter_name' => 'Объем', 'parameter_value' => '66 л'],
                    ['parameter_name' => 'Тип очистки', 'parameter_value' => 'Каталитическая'],
                    ['parameter_name' => 'Класс энергопотребления', 'parameter_value' => 'A'],
                ],
            ],
            [
                'name' => 'Кондиционер Midea MSAG1-09HRN1',
                'category' => 'Кондиционеры',
                'manufacturer' => 'Midea',
                'manufacture_date' => '2025-04-08',
                'warranty_months' => 36,
                'price_rub' => 29990.00,
                'price_usd' => 329.00,
                'discount_percent' => 0,
                'discount_reason' => null,
                'specifications' => [
                    ['parameter_name' => 'Площадь помещения', 'parameter_value' => 'до 25 м²'],
                    ['parameter_name' => 'Инвертор', 'parameter_value' => 'Нет'],
                    ['parameter_name' => 'Класс энергоэффективности', 'parameter_value' => 'A'],
                ],
            ],
            [
                'name' => 'Миксер кухонный ATLANT МК-7001',
                'category' => 'Мелкая кухонная техника',
                'manufacturer' => 'ATLANT',
                'manufacture_date' => '2024-09-14',
                'warranty_months' => 12,
                'price_rub' => 5990.00,
                'price_usd' => 69.00,
                'discount_percent' => 0,
                'discount_reason' => null,
                'specifications' => [
                    ['parameter_name' => 'Мощность', 'parameter_value' => '700 Вт'],
                    ['parameter_name' => 'Количество скоростей', 'parameter_value' => '5'],
                    ['parameter_name' => 'Чаша', 'parameter_value' => '3 л'],
                ],
            ],
        ];

        foreach ($products as $item) {
            $categoryId = $categoriesByName[$item['category']] ?? null;
            $manufacturerId = $manufacturersByName[$item['manufacturer']] ?? null;
            if ($categoryId === null || $manufacturerId === null) {
                continue;
            }

            $product = Product::query()->updateOrCreate(
                ['name' => $item['name']],
                [
                    'category_id' => $categoryId,
                    'manufacturer_id' => $manufacturerId,
                    'manufacture_date' => $item['manufacture_date'],
                    'warranty_months' => $item['warranty_months'],
                    'price_rub' => $item['price_rub'],
                    'price_usd' => $item['price_usd'],
                    'discount_percent' => $item['discount_percent'],
                    'discount_reason' => $item['discount_reason'],
                ]
            );

            $product->specifications()->delete();
            $product->specifications()->createMany($item['specifications']);

            if ($storageLocations->isNotEmpty()) {
                $product->stocks()->delete();

                foreach ($storageLocations->take(2) as $location) {
                    $product->stocks()->create([
                        'storage_location_id' => $location->id,
                        'quantity' => random_int(1, 25),
                    ]);
                }
            }
        }
    }
}
