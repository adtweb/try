<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Http\Requests\ParticipantUpdateRequest;
use App\MoonShine\Pages\Participant\ParticipantDetailPage;
use App\MoonShine\Pages\Participant\ParticipantFormPage;
use App\MoonShine\Pages\Participant\ParticipantIndexPage;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Pages\Page;
use MoonShine\Resources\ModelResource;
use Src\Domains\Auth\Models\Participant;

/**
 * @extends ModelResource<Participant>
 */
class ParticipantResource extends ModelResource
{
    protected string $model = Participant::class;

    protected string $title = 'Участники';

    protected string $column = 'id';

    protected array $with = ['user'];

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            ParticipantIndexPage::make($this->title()),
            ParticipantFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            ParticipantDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param  Participant  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return (new ParticipantUpdateRequest)->rules();
    }

    public function getActiveActions(): array
    {
        return ['view', 'update', 'create'];
    }

    public function search(): array
    {
        return [
            'name_ru',
            'surname_ru',
            'middle_name_ru',
            'name_en',
            'surname_en',
            'middle_name_en',
        ];
    }
}
