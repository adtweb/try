<?php

namespace Tests\Feature;

use App\Http\Controllers\ThesisController;
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
use Illuminate\Support\Facades\Notification;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Enums\ConferenceReportForm;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Participation;
use Src\Domains\Conferences\Models\Section;
use Src\Domains\Conferences\Models\Thesis;
use Tests\TestCase;

class ThesisUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $participantUser;

    protected User $organizationUser;

    protected Conference $conference;

    protected Participation $participation;

    protected Organization $organization;

    protected Section $section;

    protected Thesis $thesis;

    protected array $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SubjectSeeder::class);
        $this->seed(ConferenceTypeSeeder::class);

        $this->organizationUser = UserFactory::new()->create();
        $this->participantUser = UserFactory::new()->create();
        $this->organization = OrganizationFactory::new()->create();
        $this->conference = ConferenceFactory::new()->create([
            'organization_id' => $this->organization->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDay(),
            'thesis_accept_until' => now()->addDay(),
            'thesis_edit_until' => now()->addDay(),
        ]);

        $section = SectionFactory::new()
            ->create([
                'conference_id' => $this->conference->id,
                'slug' => 'FRST',
            ]);

        $this->section = SectionFactory::new()
            ->create([
                'conference_id' => $this->conference->id,
                'slug' => 'SCND',
            ]);

        $participant = ParticipantFactory::new()->create([
            'user_id' => $this->participantUser->id,
        ]);
        $this->participation = ParticipationFactory::new()->create([
            'participant_id' => $participant->id,
            'conference_id' => $this->conference->id,
        ]);

        $this->data = [
            'participation_id' => $this->participation->id,
            'report_form' => ConferenceReportForm::any->value,
            'section_id' => $section->id,
            'solicited_talk' => true,
            'title' => '<p>Some title <sub>123</sub></p>',
            'authors' => [
                1 => [
                    'name_ru' => 'Антон',
                    'surname_ru' => 'Леонтьев',
                    'middle_name_ru' => '',
                    'name_en' => 'Anton',
                    'surname_en' => 'Leontev',
                    'middle_name_en' => '',
                ],
            ],
            'reporter' => ['id' => 1, 'is_young' => true],
            'contact' => ['id' => 1, 'email' => 'test@ya.ru'],
            'text' => '<p>some text</p>',
        ];

        $this->thesis = ThesisFactory::new()
            ->create($this->data);
    }

    public function test_changing_thesis_id_when_first_thesis_in_new_section(): void
    {
        Notification::fake();

        $data = array_merge($this->data, ['section_id' => $this->section->id]);

        $response = $this->actingAs($this->participantUser)
            ->post(action([ThesisController::class, 'update'], [$this->conference->slug, $this->thesis->id]), $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('theses', [
            'section_id' => $this->section->id,
            'thesis_id' => $this->conference->slug.'-SCND001',
        ]);
    }

    public function test_changing_thesis_id_when_not_first_thesis_in_new_section(): void
    {
        Notification::fake();

        ThesisFactory::new()
            ->count(3)
            ->create([
                'section_id' => $this->section->id,
            ]);

        $data = array_merge($this->data, ['section_id' => $this->section->id]);

        $response = $this->actingAs($this->participantUser)
            ->post(action([ThesisController::class, 'update'], [$this->conference->slug, $this->thesis->id]), $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('theses', [
            'section_id' => $this->section->id,
            'thesis_id' => $this->conference->slug.'-SCND004',
        ]);
    }
}
