<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Auth\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name_ru' => $this->faker->firstName('male'),
            'surname_ru' => $this->faker->lastName('male'),
            'middle_name_ru' => $this->faker->firstName('male'),
            'name_en' => 'Test name',
            'surname_en' => 'Test surname',
            'middle_name_en' => 'Test middlename',
            'phone' => '8-912-000-12-25',
            'orcid_id' => '0000-0000-1234-5689',
            'website' => 'http://php.net',
        ];
    }
}
