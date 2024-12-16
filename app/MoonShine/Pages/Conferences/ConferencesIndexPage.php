<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Conferences;

use App\MoonShine\Resources\OrganizationResource;
use App\MoonShine\Resources\UserResource;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Date;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\IndexPage;
use Throwable;

class ConferencesIndexPage extends IndexPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Text::make('Название', 'title_ru'),
            BelongsTo::make('Организатор', 'user', resource: new UserResource),
            BelongsTo::make('Организация', 'organization', resource: new OrganizationResource),
            Date::make('Начало', 'start_date')->format('d.m.Y'),
            Date::make('Завершение', 'end_date')->format('d.m.Y'),
            Switcher::make('Расписание опубликовано', 'schedule_is_published')
                ->updateOnPreview(),
            Switcher::make('Материалы тезисов опубликованы', 'asset_is_published')
                ->updateOnPreview(),
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
