<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Domains\Conferences\Models\ConferenceType;

class ConferenceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->list() as $titles) {
            ConferenceType::create([
                'title_ru' => $titles['ru'],
                'title_en' => $titles['en'],
            ]);
        }
    }

    private function list(): array
    {
        return [
            [
                'ru' => 'Конференция',
                'en' => 'Conference',
            ],
            [
                'ru' => 'Школа-семинар',
                'en' => 'School-seminar',
            ],
            [
                'ru' => 'Семинар',
                'en' => 'Seminar',
            ],
            [
                'ru' => 'Презентация',
                'en' => 'Presentation',
            ],
            [
                'ru' => 'Выставка',
                'en' => 'Exhibition',
            ],
            [
                'ru' => 'Мастер-класс',
                'en' => 'Master-class',
            ],
        ];
    }
}
