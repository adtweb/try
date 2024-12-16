<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fp = fopen(__DIR__.'/countries.csv', 'r');
        if ($fp) {
            while (($buffer = fgets($fp, 4096)) !== false) {
                $titles = explode(';', $buffer);
                Country::create([
                    'name_ru' => trim($titles[0]),
                    'name_en' => trim($titles[1]),
                ]);
            }
            if (! feof($fp)) {
                echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
            }
            fclose($fp);
        }
    }
}
