<?php

namespace Src\Domains\Conferences\Actions;

use App\Http\Requests\ParticipationUpdateRequest;
use Src\Domains\Conferences\Models\Participation;

class UpdateParticipation
{
    public function handle(
        Participation $participation,
        ParticipationUpdateRequest $request
    ): Participation {
        $participation->update([
            'name_ru' => $request->get('name_ru'),
            'surname_ru' => $request->get('surname_ru'),
            'middle_name_ru' => $request->get('middle_name_ru'),
            'name_en' => $request->get('name_en'),
            'surname_en' => $request->get('surname_en'),
            'middle_name_en' => $request->get('middle_name_en'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'affiliations' => $request->get('affiliations'),
            'orcid_id' => $request->get('orcid_id'),
            'website' => $request->get('website'),
            'participation_type' => $request->get('participation_type'),
            'is_young' => $request->get('is_young'),
        ]);

        return $participation;
    }
}
