<?php

namespace Src\Domains\Auth\Actions;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Auth\Models\User;

class CreateParticipant
{
    public function handle(FormRequest $request, User $user): Participant
    {
        return Participant::create([
            'user_id' => $user->id,
            'name_ru' => $request->get('name_ru'),
            'surname_ru' => $request->get('surname_ru'),
            'middle_name_ru' => $request->get('middle_name_ru'),
            'name_en' => $request->get('name_en'),
            'surname_en' => $request->get('surname_en'),
            'middle_name_en' => $request->get('middle_name_en'),
            'phone' => $request->get('phone'),
        ]);
    }
}
