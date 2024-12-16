<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\MoonShine\Pages\User\UserDetailPage;
use App\MoonShine\Pages\User\UserFormPage;
use App\MoonShine\Pages\User\UserIndexPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use MoonShine\Pages\Page;
use MoonShine\Resources\ModelResource;
use Src\Domains\Auth\Models\User;

/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';

    protected array $with = ['participant'];

    protected string $column = 'email';
    // protected bool $isAsync = true;

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            UserIndexPage::make($this->title()),
            UserFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            UserDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param  User  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($item?->id),
            ],
            'password' => [Password::default()],
            'email_verified_at',
        ];
    }

    public function getActiveActions(): array
    {
        return ['view', 'create', 'update', 'delete'];
    }

    public function search(): array
    {
        return ['email', 'participant.surname_ru', 'participant.name_ru'];
    }
}
