<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Organization;

use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use Throwable;

class OrganizationDetailPage extends DetailPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('Пользователь', 'user'),
            Text::make('Название', 'full_name_ru'),
            Text::make('', 'short_name_ru'),
            Text::make('Название EN', 'full_name_en'),
            Text::make('', 'short_name_en'),
            Text::make('ИНН', 'inn'),
            Text::make('Адрес', 'address'),
            Text::make('Телефон', 'phone', static fn ($item) => $item->phone?->clean()),
            Text::make('whatsapp'),
            Text::make('telegram'),
            Text::make('Тип', 'type'),
            Text::make('vk'),

            HasMany::make('Мероприятия', 'conferences'),
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
