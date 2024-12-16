<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Participant;

use App\MoonShine\Resources\UserResource;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Flex;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Field;
use MoonShine\Fields\Json;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\FormPage;
use Src\Domains\Auth\Models\Participant;
use Throwable;

class ParticipantFormPage extends FormPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            BelongsTo::make('Пользователь', 'user', resource: new UserResource)
                ->searchable()
                ->required(),
            Flex::make([
                Text::make('Имя', 'name_ru'),
                Text::make('Фамилия', 'surname_ru'),
                Text::make('Отчество', 'middle_name_ru'),
            ]),
            Flex::make([
                Text::make('Имя EN', 'name_en'),
                Text::make('Фамилия EN', 'surname_en'),
                Text::make('Отчество EN', 'middle_name_en'),
            ]),
            Text::make('Телефон', 'phone', fn (Participant $item) => $item->phone?->clean()),
            // Json::make('Аффилиации', 'affiliations')
            // 	->fields([
            // 		Number::make('id'),
            // 		Text::make('Название', 'title_ru'),
            // 		Text::make('Название EN', 'title_en'),
            // 		Checkbox::make('С ошибкой', 'has_mistake'),
            // 		Checkbox::make('Нет аффилиации', 'no_affiliation'),
            // 	]),
            Text::make('ORCID', 'orcid_id')
                ->mask('****-****-****-****'),
            Text::make('Сайт', 'website'),
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
