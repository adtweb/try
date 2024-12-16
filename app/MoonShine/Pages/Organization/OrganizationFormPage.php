<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Organization;

use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Flex;
use MoonShine\Fields\Field;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\FormPage;
use Throwable;

class OrganizationFormPage extends FormPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Flex::make([
                Text::make('Название', 'full_name_ru')
                    ->unescape(),
                Text::make('Сокращенное название', 'short_name_ru')
                    ->unescape(),
            ]),
            Flex::make([
                Text::make('Название EN', 'full_name_en')->unescape(),
                Text::make('Сокращенное название EN', 'short_name_en')->unescape(),
            ]),
            Text::make('ИНН', 'inn'),
            Text::make('Адрес', 'address')->unescape(),
            Text::make('Телефон', 'phone'),
            Flex::make([
                Text::make('whatsapp')->unescape(),
                Text::make('telegram')->unescape(),
                Text::make('vk')->unescape(),
            ]),
            Select::make('Тип', 'type')
                ->options([
                    'Коммерческая организация' => 'Коммерческая организация',
                    'Университет' => 'Университет',
                    'Государственное учреждение' => 'Государственное учреждение',
                    'Научно-исследовательский институт' => 'Научно-исследовательский институт',
                    'Институт' => 'Институт',
                ]),
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
