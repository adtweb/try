<?php

namespace Tests\Feature;

use App\Http\Controllers\SectionController;
use Database\Factories\ConferenceFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\ParticipantFactory;
use Database\Factories\ParticipationFactory;
use Database\Factories\SectionFactory;
use Database\Factories\ThesisFactory;
use Database\Factories\UserFactory;
use Database\Seeders\ConferenceTypeSeeder;
use Database\Seeders\SubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Models\Conference;
use Tests\TestCase;

class SectionsUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $organizationUser;

    protected Conference $conference;

    protected Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SubjectSeeder::class);
        $this->seed(ConferenceTypeSeeder::class);

        $this->organizationUser = UserFactory::new()->create();
        $this->organization = OrganizationFactory::new()->create();
        $this->conference = ConferenceFactory::new()->create([
            'user_id' => $this->organizationUser->id,
            'organization_id' => $this->organization->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDay(),
            'thesis_accept_until' => now()->addDay(),
            'thesis_edit_until' => now()->addDay(),
        ]);

    }

    public function test_creating_section_in_mass_update(): void
    {
        $response = $this->actingAs($this->organizationUser)
            ->post(action([SectionController::class, 'massUpdate'], $this->conference->slug), [
                'sections' => [
                    [
                        'title_ru' => 'Секция 1',
                        'title_en' => 'Section 1',
                        'slug' => 'SKT',
                    ],
                    [
                        'title_ru' => 'Секция 2',
                        'title_en' => 'Section 2',
                        'slug' => 'SK',
                    ],
                ],
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('sections', 2);
        $this->assertDatabaseHas('sections', [
            'title_ru' => 'Секция 1',
            'title_en' => 'Section 1',
            'slug' => 'SKT',
        ]);
        $this->assertDatabaseHas('sections', [
            'title_ru' => 'Секция 2',
            'title_en' => 'Section 2',
            'slug' => 'SK',
        ]);
    }

    public function test_updating_section_in_mass_update(): void
    {
        $section = SectionFactory::new()->create([
            'conference_id' => $this->conference->id,
        ]);

        $response = $this->actingAs($this->organizationUser)
            ->post(action([SectionController::class, 'massUpdate'], $this->conference->slug), [
                'sections' => [
                    [
                        'id' => $section->id,
                        'title_ru' => 'Секция 1',
                        'title_en' => 'Section 1',
                        'slug' => 'SKT',
                    ],
                    [
                        'title_ru' => 'Секция 2',
                        'title_en' => 'Section 2',
                        'slug' => 'SK',
                    ],
                ],
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('sections', 2);
        $this->assertDatabaseHas('sections', [
            'id' => $section->id,
            'title_ru' => 'Секция 1',
            'title_en' => 'Section 1',
            'slug' => 'SKT',
        ]);
        $this->assertDatabaseHas('sections', [
            'title_ru' => 'Секция 2',
            'title_en' => 'Section 2',
            'slug' => 'SK',
        ]);
    }

    public function test_deleting_section_in_mass_update(): void
    {
        $section = SectionFactory::new()->create([
            'conference_id' => $this->conference->id,
        ]);

        $response = $this->actingAs($this->organizationUser)
            ->post(action([SectionController::class, 'massUpdate'], $this->conference->slug), [
                'sections' => [
                    [
                        'title_ru' => 'Секция 2',
                        'title_en' => 'Section 2',
                        'slug' => 'SK',
                    ],
                ],
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('sections', 1);
        $this->assertDatabaseMissing('sections', [
            'id' => $section->id,
        ]);
    }

    public function test_not_saving_empty_sections(): void
    {
        $section = SectionFactory::new()->create([
            'conference_id' => $this->conference->id,
        ]);

        $response = $this->actingAs($this->organizationUser)
            ->postJson(action([SectionController::class, 'massUpdate'], $this->conference->slug), [
                'sections' => [],
            ]);

        $response->assertStatus(422);

        $this->assertDatabaseCount('sections', 2);
    }

    public function test_not_deleting_section_with_theses(): void
    {
        $user = UserFactory::new()->create();
        $participant = ParticipantFactory::new()->create(['user_id' => $user->id]);
        $participation = ParticipationFactory::new()->create([
            'participant_id' => $participant->id,
            'conference_id' => $this->conference->id,
        ]);
        $section = SectionFactory::new()->create([
            'conference_id' => $this->conference->id,
        ]);

        $thesis = ThesisFactory::new()->create([
            'participation_id' => $participation->id,
            'section_id' => $section->id,
        ]);

        $response = $this->actingAs($this->organizationUser)
            ->post(action([SectionController::class, 'massUpdate'], $this->conference->slug), [
                'sections' => [
                    [
                        'title_ru' => 'Секция 2',
                        'title_en' => 'Section 2',
                        'slug' => 'SK',
                    ],
                ],
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('sections', 2);
        $this->assertDatabaseHas('sections', [
            'id' => $section->id,
            'title_ru' => $section->title_ru,
            'title_en' => $section->title_en,
            'slug' => $section->slug,
        ]);
        $this->assertDatabaseHas('sections', [
            'title_ru' => 'Секция 2',
            'title_en' => 'Section 2',
            'slug' => 'SK',
        ]);
    }
}
