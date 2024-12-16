<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Domains\Conferences\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->list() as $titles) {
            Subject::create([
                'title_ru' => $titles['ru'],
                'title_en' => $titles['en'],
            ]);
        }
    }

    private function list(): array
    {
        return [
            [
                'ru' => 'Математика, информатика и науки о системах',
                'en' => 'Mathematics, computer science and systems sciences',
            ],
            [
                'ru' => 'Физика и науки о космосе',
                'en' => 'Physics and Space Sciences',
            ],
            [
                'ru' => 'Химия и науки о материалах',
                'en' => 'Chemistry and Materials Sciences',
            ],
            [
                'ru' => 'Биология и науки о жизни',
                'en' => 'Biology and Life Sciences',
            ],
            [
                'ru' => 'Фундаментальные исследования для медицины',
                'en' => 'Basic research for medicine',
            ],
            [
                'ru' => 'Сельскохозяйственные науки',
                'en' => 'Agricultural Sciences',
            ],
            [
                'ru' => 'Науки о Земле',
                'en' => 'Geosciences',
            ],
            [
                'ru' => 'Гуманитарные и социальные науки',
                'en' => 'Humanities and social sciences',
            ],
            [
                'ru' => 'Инженерные науки',
                'en' => 'Engineering Sciences',
            ],
        ];
    }
}
