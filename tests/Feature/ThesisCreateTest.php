<?php

namespace Tests\Feature;

use App\Http\Controllers\ThesisController;
use App\Notifications\ThesisCreatedOrganizationNotification;
use App\Notifications\ThesisCreatedParticipantNotification;
use Database\Factories\ConferenceFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\ParticipantFactory;
use Database\Factories\ParticipationFactory;
use Database\Factories\SectionFactory;
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
use Tests\TestCase;

class ThesisCreateTest extends TestCase
{
    use RefreshDatabase;

    protected User $participantUser;

    protected User $organizationUser;

    protected Conference $conference;

    protected Participation $participation;

    protected Organization $organization;

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
    }

    public function test_creating_thesis_with_sections(): void
    {
        Notification::fake();

        $firstSection = SectionFactory::new()->create(['conference_id' => $this->conference->id]);
        $secondSection = SectionFactory::new()->create(['conference_id' => $this->conference->id]);

        $data = array_merge($this->data, ['section_id' => $firstSection->id]);

        $response = $this->actingAs($this->participantUser)->post(action([ThesisController::class, 'store'], $this->conference->slug), $data);

        $response->assertJson(['redirect' => route('conference.show', $this->conference->slug)]);
        $response->assertOk();

        $this->assertDatabaseHas('theses', [
            'thesis_id' => "{$this->conference->slug}-{$firstSection->slug}001",
        ]);

        // Notification::assertSentTo($this->participantUser, ThesisCreatedParticipantNotification::class);
        // Notification::assertSentTo($this->organizationUser, ThesisCreatedOrganizationNotification::class);

        $data['section_id'] = $secondSection->id;

        $response = $this->actingAs($this->participantUser)->post(action([ThesisController::class, 'store'], $this->conference->slug), $data);

        $this->assertDatabaseCount('theses', 2);
        $this->assertDatabaseHas('theses', [
            'thesis_id' => "{$this->conference->slug}-{$secondSection->slug}001",
        ]);
    }

    // public function test_creating_thesis_without_sections(): void
    // {
    //     Notification::fake();

    //     $response = $this->actingAs($this->participantUser)->post(action([ThesisController::class, 'store'], $this->conference->slug), $this->data);

    //     $response->assertJson(['redirect' => route('conference.show', $this->conference->slug)]);
    //     $response->assertOk();

    //     $this->assertDatabaseHas('theses', [
    //         'thesis_id' => "{$this->conference->slug}001",
    //     ]);

    //     // Notification::assertSentTo($this->participantUser, ThesisCreatedParticipantNotification::class);
    //     // Notification::assertSentTo($this->organizationUser, ThesisCreatedOrganizationNotification::class);
    // }

    public function test_fail_by_conference_thesis_accept_date(): void
    {
        $conference = ConferenceFactory::new()->create([
            'user_id' => $this->organizationUser->id,
            'organization_id' => $this->organization->id,
            'start_date' => now()->subDay(),
            'end_date' => now()->subDay(),
            'thesis_accept_until' => now()->subDay(),
            'thesis_edit_until' => now()->subDay(),
            'assets_load_until' => now()->addDay(),
        ]);

        $participation = ParticipationFactory::new()->create([
            'participant_id' => $this->participantUser->participant->id,
            'conference_id' => $conference->id,
        ]);

        $response = $this->actingAs($this->participantUser)->post(action([ThesisController::class, 'store'], $conference->slug), [
            'participation_id' => $participation->id,
            'report_form' => ConferenceReportForm::any->value,
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
        ]);

        $response->assertBadRequest();
    }
}
