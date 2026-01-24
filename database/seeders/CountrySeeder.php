<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Seed the application's database with countries.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Россия', 'code' => 'RU'],
            ['name' => 'Беларусь', 'code' => 'BY'],
            ['name' => 'Казахстан', 'code' => 'KZ'],
            ['name' => 'Китай', 'code' => 'CN'],
            ['name' => 'Германия', 'code' => 'DE'],
            ['name' => 'Италия', 'code' => 'IT'],
            ['name' => 'Турция', 'code' => 'TR'],
            ['name' => 'Польша', 'code' => 'PL'],
            ['name' => 'Франция', 'code' => 'FR'],
            ['name' => 'США', 'code' => 'US'],
        ];

        Country::query()->insert($countries);
    }
}
