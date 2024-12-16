<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domains\Conferences\Enums\ConferenceReportForm;
use Src\Domains\Conferences\Models\Participation;
use Src\Domains\Conferences\Models\Thesis;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ThesisFactory extends Factory
{
    protected $model = Thesis::class;

    public function definition(): array
    {
        $participation = Participation::inRandomOrder()->first();
        $sectionsIds = $participation->conference->sections->pluck('id')->toArray();

        return [
            'thesis_id' => $this->faker->word(),
            'section_id' => $this->faker->randomElement($sectionsIds),
            'participation_id' => $participation->id,
            'report_form' => $this->faker->randomElement(ConferenceReportForm::cases()),
            'solicited_talk' => $this->faker->boolean(),
            'title' => $this->faker->words(random_int(2, 5), true),
            'authors' => ['1' => [
                'name_en' => 'Anton',
                'surname_en' => 'Leontev',
                'middle_name_en' => null,
                'name_ru' => 'Антон',
                'surname_ru' => 'Леонтьев',
                'middle_name_ru' => null,
                'affiliations' => [],
            ]],
            'reporter' => ['id' => 1, 'is_young' => $this->faker->boolean()],
            'contact' => ['id' => 1, 'email' => $this->faker->safeEmail()],
            'text' => $this->faker->realText(400),
        ];
    }
}
