<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Field;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use Src\Domains\Conferences\Models\Section;

/**
 * @extends ModelResource<Section>
 */
class SectionResource extends ModelResource
{
    protected string $model = Section::class;

    protected string $title = 'Секции';

    protected bool $isAsync = true;

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                Text::make('Название', 'title_ru')
                    ->updateOnPreview(),
                Text::make('Название EN', 'title_en')
                    ->updateOnPreview(),
                Text::make('Акроним', 'slug')
                    ->updateOnPreview(),
            ]),
        ];
    }

    /**
     * @param  Section  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'slug' => ['required', 'string', 'max:15'],
            'title_ru' => ['required', 'string', 'regex:~[а-яА-Я0-9\-_ ]+~u'],
            'title_en' => ['required', 'string', 'regex:~[a-zA-Z0-9\-_ ]+~u'],
        ];
    }

    public function getActiveActions(): array
    {
        return ['delete', 'create'];
    }

    public function search(): array
    {
        return [];
    }
}
