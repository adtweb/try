<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Domains\Conferences\Models\Affiliation;

class AffiliationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fp = fopen(__DIR__.'/univer.csv', 'r');
        if ($fp) {
            while (($buffer = fgets($fp, 4096)) !== false) {
                $titles = explode(';', $buffer);
                Affiliation::create([
                    'title_ru' => $titles[0],
                    'title_en' => $titles[1],
                ]);
            }
            if (! feof($fp)) {
                echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
            }
            fclose($fp);
        }
    }
}
