<?php

namespace Src\Domains\Conferences\Actions;

use Illuminate\Http\Request;
use Src\Domains\Conferences\Models\Participation;

class CreateParticipation
{
    public function handle(Request $request): Participation
    {
        return Participation::create([
            'participant_id' => $request->get('participant_id'),
            'conference_id' => $request->get('conference_id'),
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
    }
}
