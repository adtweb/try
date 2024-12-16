<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isLocal()) {
            Model::shouldBeStrict();
        }

        Password::defaults(function () {
            $rule = Password::min(8);

            return $rule->mixedCase()->letters()->numbers();
        });

        Relation::enforceMorphMap([
            'participant' => 'Src\Domains\Auth\Models\Participant',
            'organization' => 'Src\Domains\Auth\Models\Organization',
            'section' => 'Src\Domains\Conferences\Models\Section',
            'conference' => 'Src\Domains\Conferences\Models\Conference',
            'MoonShine\Models\MoonshineUser' => 'MoonShine\Models\MoonshineUser',
        ]);
    }
}
