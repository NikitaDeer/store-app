<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    /**
     * Seed the application's database with manufacturers.
     */
    public function run(): void
    {
        $countriesByCode = Country::query()->pluck('id', 'code');

        $manufacturers = [
            ['name' => 'Bosch', 'country_code' => 'DE'],
            ['name' => 'Siemens', 'country_code' => 'DE'],
            ['name' => 'Beko', 'country_code' => 'TR'],
            ['name' => 'Ariston', 'country_code' => 'IT'],
            ['name' => 'Indesit', 'country_code' => 'IT'],
            ['name' => 'Whirlpool', 'country_code' => 'US'],
            ['name' => 'Haier', 'country_code' => 'CN'],
            ['name' => 'Midea', 'country_code' => 'CN'],
            ['name' => 'Hansa', 'country_code' => 'PL'],
            ['name' => 'ATLANT', 'country_code' => 'BY'],
            ['name' => 'Бирюса', 'country_code' => 'RU'],
        ];

        foreach ($manufacturers as $manufacturer) {
            $countryId = $countriesByCode[$manufacturer['country_code']] ?? null;
            if ($countryId === null) {
                continue;
            }

            Manufacturer::query()->updateOrCreate(
                ['name' => $manufacturer['name']],
                ['country_id' => $countryId]
            );
        }
    }
}
