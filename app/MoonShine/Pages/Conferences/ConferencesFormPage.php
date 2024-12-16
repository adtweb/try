<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Conferences;

use App\Enums\Timezone;
use App\MoonShine\Resources\ConferenceTypeResource;
use App\MoonShine\Resources\SectionResource;
use App\MoonShine\Resources\SubjectResource;
use App\MoonShine\Resources\UserResource;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Flex;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Enum;
use MoonShine\Fields\Field;
use MoonShine\Fields\Number;
use MoonShine\Fields\Phone;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Pages\Crud\FormPage;
use Src\Domains\Conferences\Enums\AbstractsFormat;
use Src\Domains\Conferences\Enums\AbstractsLanguage;
use Src\Domains\Conferences\Enums\ConferenceFormat;
use Src\Domains\Conferences\Enums\ConferenceLanguage;
use Src\Domains\Conferences\Enums\ConferenceReportForm;
use Src\Domains\Conferences\Enums\ParticipantsNumber;
use Throwable;

class ConferencesFormPage extends FormPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Flex::make([
                Text::make('Название', 'title_ru')->unescape(),
                Text::make('Название EN', 'title_en')->unescape(),
            ]),
            BelongsTo::make('Пользователь', 'user', resource: new UserResource)
                ->searchable(),
            BelongsTo::make('Организация', 'organization')
                ->searchable(),
            Text::make('Акроним', 'slug'),
            BelongsTo::make('Тип', 'type', resource: new ConferenceTypeResource),
            BelongsToMany::make('Тематика мероприятия', 'subjects', resource: new SubjectResource)
                ->selectMode(),
            HasMany::make('Секции', 'sections', resource: new SectionResource)
                ->creatable(),
            Enum::make('Формат', 'format')
                ->attach(ConferenceFormat::class),
            Checkbox::make('С международным участием', 'with_foreign_participation'),
            Flex::make([
                Date::make('Начало', 'start_date')->format('d.m.Y'),
                Date::make('Завершение', 'end_date')->format('d.m.Y'),
            ]),
            Flex::make([
                Date::make('Последний день подачи тезисов', 'thesis_accept_until')->format('d.m.Y'),
                Date::make('Последний день изменения тезисов', 'thesis_edit_until')->format('d.m.Y'),
                Date::make('Последний день загрузки материалов к тезисам', 'assets_load_until')->format('d.m.Y'),
            ]),
            Enum::make('Часовой пояс', 'timezone')
                ->attach(Timezone::class),
            Text::make('Сайт', 'website')->unescape()->copy(),
            // Select::make('', 'co-organizers')
            // 	->changeFill(function($item) {
            // 		return $item->{'co-organizers'};
            // 	})
            // 	->options([])
            // 	->showOnUpdate()
            // ,
            Text::make('Адрес', 'address')->unescape(),
            Phone::make('Телефон', 'phone'),
            Email::make('email')->unescape(),
            Flex::make([
                Textarea::make('Описание', 'description_ru')
                    ->unescape()
                    ->customAttributes(['rows' => 8]),
                Textarea::make('Описание EN', 'description_en')
                    ->unescape()
                    ->customAttributes(['rows' => 8]),
            ]),
            Enum::make('Язык конференции', 'lang')
                ->attach(ConferenceLanguage::class),
            Enum::make('Формы докладов', 'report_form')
                ->attach(ConferenceReportForm::class),
            Enum::make('Количество участников', 'participants_number')
                ->attach(ParticipantsNumber::class),
            Flex::make([
                Text::make('Whatsapp')->unescape(),
                Text::make('Telegram')->unescape(),
            ]),
            Flex::make([
                Text::make('Цена для участников', 'price_participants')->unescape(),
                Text::make('Цена для посетителей', 'price_visitors')->unescape(),
                Text::make('Оплата тезисов от участников', 'abstracts_price')->unescape(),
            ]),
            Flex::make([
                Number::make('Максимальное количество символов в тексте тезисов', 'max_thesis_characters'),
                Enum::make('Язык сборника тезисов', 'abstracts_lang')
                    ->attach(AbstractsLanguage::class),
                Enum::make('Формат сборника тезисов', 'abstracts_format')
                    ->attach(AbstractsFormat::class),
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
