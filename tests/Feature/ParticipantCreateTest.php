<?php

namespace Tests\Feature;

use App\Http\Controllers\ParticipantController;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_participant(): void
    {
        $user = UserFactory::new()->create();

        $response = $this->actingAs($user)
            ->post(action([ParticipantController::class, 'store']), [
                'name_ru' => 'Имя',
                'surname_ru' => 'Фамилия',
                'middle_name_ru' => 'Отчество',
                'name_en' => 'Name',
                'surname_en' => 'Surname',
                'middle_name_en' => 'Middle',
                'phone' => '8912-651-89-89',
                'orcid_id' => '1234-0000-1234-5699',
                'website' => 'http://some.site',
            ]);

        $response->assertOk();
        $response->assertExactJson(['redirect' => route('participant.edit')]);
        $this->assertDatabaseHas('participants', [
            'user_id' => $user->id,
            'name_ru' => 'Имя',
            'surname_ru' => 'Фамилия',
            'middle_name_ru' => 'Отчество',
            'name_en' => 'Name',
            'surname_en' => 'Surname',
            'middle_name_en' => 'Middle',
            'phone' => '8912-651-89-89',
            'orcid_id' => '1234-0000-1234-5699',
            'website' => 'http://some.site',
        ]);
    }
}
