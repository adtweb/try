<?php

namespace App\Policies;

use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Models\Section;

class SectionPolicy
{
    public function delete(User $user, Section $section): bool
    {
        return $section->conference->user_id === $user->id;
    }

    public function editSectionSchedule(User $user, Section $section): bool
    {
        $conference = $section->conference;

        if ($conference->user_id === auth()->id()) {
            return true;
        }

        $sectionsIds = $user->moderatedSections->pluck('id')->toArray();

        return in_array($section->id, $sectionsIds);
    }
}
