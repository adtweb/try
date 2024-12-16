<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SectionFactory extends Factory
{
    protected $model = Section::class;

    public function definition(): array
    {
        return [
            'conference_id' => Conference::inRandomOrder()->first()->id,
            'title_ru' => $this->faker->jobTitle(),
            'title_en' => $this->faker->jobTitle(),
            'slug' => $this->faker->word(),
        ];
    }
}
