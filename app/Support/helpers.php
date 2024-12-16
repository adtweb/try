<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\ConferenceType;
use Src\Domains\Conferences\Models\Participation;
use Src\Domains\Conferences\Models\Subject;

if (! function_exists('subjects')) {
    function subjects(): Collection
    {
        if (cache()->has('subjects')) {
            return cache('subjects');
        }

        $subjects = Subject::all();

        cache(['subjects' => $subjects]);

        return $subjects;
    }
}

if (! function_exists('conference_types')) {
    function conference_types(): Collection
    {
        if (cache()->has('conference_types')) {
            return cache('conference_types');
        }

        $types = ConferenceType::all();

        cache(['conference_types' => $types]);

        return $types;
    }
}

if (! function_exists('loc')) {
    /**
     * Returns current locale. Alias for app()->getLocale()
     */
    function loc(): string
    {
        return app()->getLocale();
    }
}

if (! function_exists('localize_url')) {
    function localize_url(string $url): string
    {
        return LaravelLocalization::localizeURL($url);
    }
}

if (! function_exists('localize_route')) {
    function localize_route(string $name, mixed $parameters = []): string
    {
        return LaravelLocalization::localizeURL(route($name, $parameters));
    }
}

if (! function_exists('user_participation')) {
    /**
     * Возвращает экземпляр Participation для конференции, если он есть у авторизованного пользователя
     */
    function user_participation(Conference $conference): ?Participation
    {
        if (! auth()->check()) {
            return null;
        }

        if (is_null(auth()->user()->participant)) {
            return null;
        }

        return Participation::where('participant_id', auth()->user()->participant->id)
            ->where('conference_id', $conference->id)
            ->first();
    }
}

if (! function_exists('participant')) {
    /**
     * Возвращает экземпляр Participant если он есть у авторизованного пользователя
     */
    function participant(): ?Participant
    {
        if (! auth()->check()) {
            return null;
        }

        return auth()->user()->participant;
    }
}

if (! function_exists('user_sent_thesis')) {
    function user_sent_thesis(Conference $conference): bool
    {
        if (! auth()->check()) {
            return false;
        }

        if (is_null(auth()->user()->participant)) {
            return false;
        }

        $participation = Participation::where('participant_id', auth()->user()->participant->id)
            ->where('conference_id', $conference->id)
            ->first();

        if (is_null($participation)) {
            return false;
        }

        return $participation->thesis()->exists();
    }
}

if (! function_exists('period')) {
    function period(Carbon $start, Carbon $end): CarbonPeriod
    {
        return CarbonPeriod::create($start, $end);
    }
}
