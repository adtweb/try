<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Conferences\Models\ConferenceType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'full_name_ru' => $this->faker->company(),
            'short_name_ru' => $this->faker->companySuffix(),
            'full_name_en' => $this->faker->company(),
            'short_name_en' => $this->faker->companySuffix(),
            'inn' => $this->faker->inn10(),
            'address' => $this->faker->address,
            'phone' => $this->faker->e164PhoneNumber(),
            'type' => ConferenceType::inRandomOrder()->first()->id,
            'actions' => json_encode(['Коммерческая деятельность'], JSON_UNESCAPED_UNICODE),
        ];
    }
}
