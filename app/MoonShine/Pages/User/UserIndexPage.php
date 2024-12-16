<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\User;

use MoonShine\Components\MoonShineComponent;
use MoonShine\Components\Url as ComponentsUrl;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Template;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\IndexPage;
use Src\Domains\Auth\Models\User;
use Throwable;

class UserIndexPage extends IndexPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Text::make('email')
                ->sortable(),
            Checkbox::make('Email подтвержден', 'email_verified_at', fn ($item) => (bool) $item),
            Template::make('Участник')
                ->changeFill(static fn (User $data) => $data->participant)
                ->changePreview(static function ($data) {
                    return $data
                    ? new ComponentsUrl(
                        route('moonshine.resource.page', [
                            'participant-resource',
                            'participant-detail-page',
                            'resourceItem' => $data?->id,
                        ]),
                        trim($data?->name_ru.' '.$data?->surname_ru) ?: 'Имя не заполнено'
                    )
                    : null;
                }),
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
