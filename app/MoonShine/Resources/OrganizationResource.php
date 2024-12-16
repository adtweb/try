<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\MoonShine\Pages\Organization\OrganizationDetailPage;
use App\MoonShine\Pages\Organization\OrganizationFormPage;
use App\MoonShine\Pages\Organization\OrganizationIndexPage;
use App\Rules\Phone;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Pages\Page;
use MoonShine\Resources\ModelResource;
use Src\Domains\Auth\Models\Organization;

/**
 * @extends ModelResource<Organization>
 */
class OrganizationResource extends ModelResource
{
    protected string $model = Organization::class;

    protected string $title = 'Организации';

    protected string $column = 'full_name_ru';

    protected array $with = ['conferences'];

    protected bool $isAsync = true;

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            OrganizationIndexPage::make($this->title()),
            OrganizationFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            OrganizationDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param  Organization  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'full_name_ru' => ['required', 'string', 'max:255', 'regex:~^[^a-z]+$~u'],
            'short_name_ru' => ['nullable', 'string', 'max:255', 'regex:~^[^a-z]+$~u'],
            'full_name_en' => ['required', 'string', 'max:255', 'regex:~^[^а-яА-Я]+$~u'],
            'short_name_en' => ['nullable', 'string', 'max:255', 'regex:~^[^а-яА-Я]+$~u'],
            'inn' => ['nullable', 'numeric', 'digits_between:10,12'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', new Phone],
            'whatsapp' => ['nullable', 'string', 'max:255', 'url'],
            'telegram' => ['nullable', 'string', 'max:255', 'url'],
            'type' => ['required', 'string', 'max:255'],
            'vk' => ['nullable', 'string', 'max:255', 'url'],
            'logo' => ['nullable', 'image'],
        ];
    }

    public function getActiveActions(): array
    {
        return ['view', 'update', 'create', 'delete'];
    }

    public function search(): array
    {
        return [
            'full_name_ru',
            'short_name_ru',
            'full_name_en',
            'short_name_en',
            'inn',
        ];
    }
}
