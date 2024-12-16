<?php

namespace Tests\Feature;

use App\Http\Controllers\ParticipantController;
use Database\Factories\ParticipantFactory;
use Database\Factories\UserFactory;
use Tests\TestCase;

class ParticipantUpdateTest extends TestCase
{
    public function test_update_participant(): void
    {
        $user = UserFactory::new()->create();
        $participant = ParticipantFactory::new()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post(action([ParticipantController::class, 'update']), [
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
