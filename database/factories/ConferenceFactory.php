<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Enums\AbstractsFormat;
use Src\Domains\Conferences\Enums\AbstractsLanguage;
use Src\Domains\Conferences\Enums\ConferenceFormat;
use Src\Domains\Conferences\Enums\ConferenceLanguage;
use Src\Domains\Conferences\Enums\ConferenceReportForm;
use Src\Domains\Conferences\Enums\ParticipantsNumber;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\ConferenceType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ConferenceFactory extends Factory
{
    protected $model = Conference::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-10 days', '+5 days');
        $end = $this->faker->dateTimeBetween($start, $start->modify('+5 days'));

        $accept = $this->faker->dateTimeBetween($start->modify('-10 days'), $start);
        $edit = $this->faker->dateTimeBetween($accept, $accept->modify('+5 days'));
        $load = $this->faker->dateTimeBetween($edit, $edit->modify('+5 days'));

        return [
            'organization_id' => Organization::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'title_ru' => 'Конференция',
            'title_en' => 'Conference',
            'slug' => $this->faker->slug(2, false),
            'conference_type_id' => ConferenceType::inRandomOrder()->first()->id,
            'format' => fake()->randomElement(ConferenceFormat::values()),
            'with_foreign_participation' => true,
            'need_site' => $this->faker->boolean(),
            'co-organizers' => ['test1', 'test2'],
            'address' => 'Москва',
            'phone' => '+7-912-000-56-23',
            'email' => 'aner-ant@ya.ru',
            'start_date' => $start,
            'end_date' => $end,
            'description_ru' => $this->faker->realText(300),
            'description_en' => $this->faker->realText(300),
            'lang' => fake()->randomElement(ConferenceLanguage::values()),
            'participants_number' => fake()->randomElement(ParticipantsNumber::values()),
            'report_form' => fake()->randomElement(ConferenceReportForm::values()),
            'price_participants' => 250,
            'price_visitors' => $this->faker->boolean() ? null : $this->faker->numberBetween(100, 5000),
            'discount_students' => ['amount' => 250, 'unit' => 'RUB'],
            'discount_participants' => ['amount' => 50, 'unit' => 'RUB'],
            'discount_special_guest' => ['amount' => 50, 'unit' => 'percent'],
            'discount_young_scientist' => ['amount' => 0, 'unit' => 'RUB'],
            'abstracts_price' => 500,
            'abstracts_format' => fake()->randomElement(AbstractsFormat::values()),
            'abstracts_lang' => fake()->randomElement(AbstractsLanguage::values()),
            'max_thesis_characters' => $this->faker->numberBetween(1000, 5000),
            'thesis_instruction' => $this->faker->realText(300),
            'thesis_accept_until' => $accept,
            'thesis_edit_until' => $edit,
            'assets_load_until' => $load,
        ];
    }
}
