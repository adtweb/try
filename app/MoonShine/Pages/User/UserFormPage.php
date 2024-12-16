<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\User;

use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Field;
use MoonShine\Fields\Password;
use MoonShine\Pages\Crud\FormPage;
use Throwable;

class UserFormPage extends FormPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Email::make('Email'),
            Password::make('Пароль', 'password'),
            Date::make('Email подтвержден', 'email_verified_at')
                ->nullable(),
        ];
    }

    /**
     * @return list<MoonShineComponent>
     *
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<MoonShineComponent>
     *
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<MoonShineComponent>
     *
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
