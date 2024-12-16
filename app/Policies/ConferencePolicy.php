<?php

namespace App\Policies;

use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Models\Conference;

class ConferencePolicy
{
    public function create(User $user): bool
    {
        return ! is_null($user->email_verified_at);
    }

    public function update(User $user, Conference $conference): bool
    {
        return $conference->user_id === $user->id;
    }

    public function viewAbstracts(User $user, Conference $conference): bool
    {
        // Если создатель конференции
        if ($conference->user_id === $user->id) {
            return true;
        }

        //Если модератор секции
        $moderatedSection = $user->moderatedSections
            ->first(fn ($section) => $section->conference_id === $conference->id);

        if (! is_null($moderatedSection)) {
            return true;
        }

        return false;
    }

    public function updateAbstracts(User $user, Conference $conference): bool
    {
        return $conference->thesis_edit_until->endOfDay()->isFuture();
    }

    public function viewParticipations(User $user, Conference $conference): bool
    {
        // Если создатель конференции
        if ($conference->user_id === $user->id) {
            return true;
        }

        //Если модератор секции
        $moderatedSection = $user->moderatedSections
            ->first(fn ($section) => $section->conference_id === $conference->id);

        if (! is_null($moderatedSection)) {
            return true;
        }

        return false;
    }

    public function massSectionUpdate(User $user, Conference $conference): bool
    {
        return $conference->user_id === $user->id;
    }

    public function viewSchedules(User $user, Conference $conference): bool
    {
        // Если создатель конференции
        if ($conference->user_id === $user->id) {
            return true;
        }

        //Если модератор секции
        $moderatedSection = $user->moderatedSections
            ->first(fn ($section) => $section->conference_id === $conference->id);

        if (! is_null($moderatedSection)) {
            return true;
        }

        return false;
    }

    public function createSchedule(User $user, Conference $conference): bool
    {
        return $conference->user_id === $user->id;
    }

    public function editSchedule(User $user, Conference $conference): bool
    {
        return $conference->user_id === $user->id;
    }

    public function changeThesisAssets(User $user, Conference $conference)
    {
        // Если создатель конференции
        if ($conference->user_id === $user->id) {
            return $conference->assets_load_until
                ->addMonths(config('setup.month_to_load_assets'))
                ->endOfDay()
                ->isFuture();
        }

        return $conference->assets_load_until->endOfDay()->isFuture();
    }

    public function publishThesisAssets(User $user, Conference $conference)
    {
        return $conference->user_id === $user->id;
    }
}
