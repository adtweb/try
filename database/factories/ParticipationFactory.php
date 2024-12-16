<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Conferences\Enums\ParticipationType;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Participation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participation>
 */
class ParticipationFactory extends Factory
{
    protected $model = Participation::class;

    public function definition(): array
    {
        return [
            'participant_id' => Participant::inRandomOrder()->first()->id,
            'conference_id' => Conference::inRandomOrder()->first()->id,
            'name_ru' => $this->faker->name(),
            'surname_ru' => $this->faker->lastName(),
            'middle_name_ru' => $this->faker->lastName(),
            'name_en' => $this->faker->name(),
            'surname_en' => $this->faker->lastName(),
            'middle_name_en' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'phone' => '89126510000',
            'affiliations' => null,
            'orcid_id' => null,
            'website' => $this->faker->url(),
            'participation_type' => $this->faker->randomElement(ParticipationType::cases()),
            'is_young' => $this->faker->boolean(),
        ];
    }
}
