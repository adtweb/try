<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Participant;

use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\IndexPage;
use Throwable;

class ParticipantIndexPage extends IndexPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('Пользователь', 'user'),
            Text::make('Фамилия', 'surname_ru'),
            Text::make('Имя', 'name_ru'),
            Text::make('Отчество', 'middle_name_ru'),
            Text::make('Фамилия EN', 'surname_en'),
            Text::make('Имя EN', 'name_en'),
            Text::make('Отчество EN', 'middle_name_en'),
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
