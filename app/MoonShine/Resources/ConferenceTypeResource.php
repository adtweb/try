<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Resources\ModelResource;
use Src\Domains\Conferences\Models\ConferenceType;

/**
 * @extends ModelResource<ConferenceType>
 */
class ConferenceTypeResource extends ModelResource
{
    protected string $model = ConferenceType::class;

    protected string $title = 'ConferenceTypes';

    protected string $column = 'title_ru';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            ]),
        ];
    }

    /**
     * @param  ConferenceType  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
