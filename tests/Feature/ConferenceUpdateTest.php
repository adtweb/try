<?php

namespace Tests\Feature;

use App\Enums\Timezone;
use App\Http\Controllers\ConferenceController;
use Database\Factories\ConferenceFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\SectionFactory;
use Database\Factories\UserFactory;
use Database\Seeders\ConferenceTypeSeeder;
use Database\Seeders\SubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domains\Conferences\Enums\AbstractsFormat;
use Src\Domains\Conferences\Enums\AbstractsLanguage;
use Src\Domains\Conferences\Models\ConferenceType;
use Src\Domains\Conferences\Models\Subject;
use Tests\TestCase;

class ConferenceUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_conference(): void
    {
        $this->seed(SubjectSeeder::class);
        $this->seed(ConferenceTypeSeeder::class);

        $user = UserFactory::new()->create();
        $organization = OrganizationFactory::new()->count(10)->create()->random();

        $conference = ConferenceFactory::new()->create([
            'need_site' => false,
            'user_id' => $user->id,
            'start_date' => now()->subDay(),
            'end_date' => now()->subDay(),
            'thesis_accept_until' => now()->subDay(),
            'thesis_edit_until' => now()->subDay(),
            'assets_load_until' => now()->addDay(),
            'format' => 'international',
        ]);

        SectionFactory::new()->create(['conference_id' => $conference->id]);

        $response = $this->actingAs($user)->post(action([ConferenceController::class, 'update'], $conference->slug), [
            'title_ru' => 'UCP Конференция',
            'title_en' => 'UCP Conference',
            'organization_id' => $organization->id,
            'with_foreign_participation' => true,
            'need_site' => 'true',
            'conference_type_id' => ConferenceType::inRandomOrder()->first()->id,
            'format' => 'national',
            'lang' => 'ru',
            'participants_number' => '50-',
            'report_form' => 'oral',
            'co-organizers' => ['test1', 'test2'],
            'address' => 'Москва',
            'phone' => '+7-912-000-56-23',
            'email' => 'aner-ant@ya.ru',
            'start_date' => '2024-01-03',
            'end_date' => '2024-01-06',
            'timezone' => Timezone::UTC_11->value,
            'description_ru' => 'Описание',
            'description_en' => 'Description',
            'price_participants' => 250,
            'price_visitors' => '',
            'discount_students' => ['amount' => 250, 'unit' => 'RUB'],
            'discount_participants' => ['amount' => 50, 'unit' => 'RUB'],
            'discount_special_guest' => ['amount' => 50, 'unit' => 'percent'],
            'discount_young_scientist' => ['amount' => 0, 'unit' => 'RUB'],
            'abstracts_price' => 500,
            'abstracts_format' => fake()->randomElement(AbstractsFormat::values()),
            'abstracts_lang' => fake()->randomElement(AbstractsLanguage::values()),
            'subjects' => Subject::inRandomOrder()->take(2)->pluck('id')->toArray(),
            'max_thesis_characters' => 3200,
            'thesis_instruction' => 'instruction-test',
            'thesis_accept_until' => '2024-01-03',
            'thesis_edit_until' => '2024-01-03',
            'assets_load_until' => '2024-02-03',
        ]);

        $response->assertOk();

        $this->assertDatabaseCount('conferences', 1);
        $this->assertDatabaseHas('conferences', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'title_ru' => 'UCP Конференция',
            'title_en' => 'UCP Conference',
            'with_foreign_participation' => 1,
            'need_site' => 1,
            'format' => 'national',
            'co-organizers' => $this->castAsJson(['test1', 'test2']),
            'address' => 'Москва',
            'phone' => '+7-912-000-56-23',
            'email' => 'aner-ant@ya.ru',
            'start_date' => '2024-01-03',
            'end_date' => '2024-01-06',
            'timezone' => Timezone::UTC_11->value,
            'description_ru' => 'Описание',
            'description_en' => 'Description',
            'price_participants' => 250,
            'price_visitors' => null,
            'discount_students' => $this->castAsJson(['amount' => 250, 'unit' => 'RUB']),
            'discount_participants' => $this->castAsJson(['amount' => 50, 'unit' => 'RUB']),
            'discount_special_guest' => $this->castAsJson(['amount' => 50, 'unit' => 'percent']),
            'discount_young_scientist' => $this->castAsJson(['amount' => 0, 'unit' => 'RUB']),
            'abstracts_price' => 500,
            'max_thesis_characters' => 3200,
            'thesis_instruction' => 'instruction-test',
            'thesis_accept_until' => '2024-01-03',
            'thesis_edit_until' => '2024-01-03',
            'assets_load_until' => '2024-02-03',
        ]);

        $this->assertDatabaseCount('conference_subject', 2);
    }
}
