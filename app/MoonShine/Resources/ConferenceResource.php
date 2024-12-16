<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\Timezone;
use App\MoonShine\Pages\Conferences\ConferencesDetailPage;
use App\MoonShine\Pages\Conferences\ConferencesFormPage;
use App\MoonShine\Pages\Conferences\ConferencesIndexPage;
use App\Rules\ConferenceDiscount;
use App\Rules\Phone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use MoonShine\Pages\Page;
use MoonShine\Resources\ModelResource;
use Src\Domains\Conferences\Enums\AbstractsFormat;
use Src\Domains\Conferences\Enums\AbstractsLanguage;
use Src\Domains\Conferences\Enums\ConferenceFormat;
use Src\Domains\Conferences\Enums\ConferenceLanguage;
use Src\Domains\Conferences\Enums\ConferenceReportForm;
use Src\Domains\Conferences\Enums\ParticipantsNumber;
use Src\Domains\Conferences\Models\Conference;

/**
 * @extends ModelResource<Conferences>
 */
class ConferenceResource extends ModelResource
{
    protected string $model = Conference::class;

    protected string $title = 'Конференции';

    protected string $column = 'title_ru';

    protected array $with = ['organization', 'sections', 'subjects', 'type', 'user'];

    protected bool $isAsync = true;

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            ConferencesIndexPage::make($this->title()),
            ConferencesFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            ConferencesDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param  Conferences  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'title_ru' => ['required', 'string', 'max:250'],
            'title_en' => ['required', 'string', 'max:250'],
            'slug' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9\-_]+$/u', Rule::unique('conferences', 'slug')->ignore($item->id)],
            'conference_type_id' => ['required', 'in:'.conference_types()->pluck('id')->join(',')],
            'format' => ['required',  Rule::enum(ConferenceFormat::class)],
            'with_foreign_participation' => ['required', 'boolean'],
            'subjects' => ['required', 'array'],
            'subjects.*' => ['required', 'int', 'in:'.subjects()->pluck('id')->join(',')],
            'sections' => ['nullable', 'array'],
            'sections.*.slug' => ['required', 'string', 'max:15'],
            'sections.*.title_ru' => ['required', 'string', 'max:255'],
            'sections.*.title_en' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image'],
            'website' => ['nullable', 'url', 'max:255'],
            // 'co-organizers' => ['nullable', 'array'],
            // 'co-organizers.*' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', new Phone],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'timezone' => ['required', Rule::enum(Timezone::class)],
            'description_ru' => ['required', 'string', 'max:1000'],
            'description_en' => ['required', 'string', 'max:1000'],
            'lang' => ['required', Rule::enum(ConferenceLanguage::class)],
            'participants_number' => ['required', Rule::enum(ParticipantsNumber::class)],
            'report_form' => ['required',  Rule::enum(ConferenceReportForm::class)],
            'whatsapp' => ['nullable', 'url', 'max:255'],
            'telegram' => ['nullable', 'url', 'max:255'],
            'price_participants' => ['nullable', 'integer', 'min:0', 'max:999999999'],
            'price_visitors' => ['nullable', 'integer', 'min:0', 'max:999999999'],
            // 'discount_students' => ['required', 'array', new ConferenceDiscount],
            // 'discount_participants' => ['required', 'array', new ConferenceDiscount],
            // 'discount_special_guest' => ['required', 'array', new ConferenceDiscount],
            // 'discount_young_scientist' => ['required', 'array', new ConferenceDiscount],
            'abstracts_price' => ['nullable', 'integer', 'min:0', 'max:999999999'],
            'abstracts_format' => ['required',  Rule::enum(AbstractsFormat::class)],
            'abstracts_lang' => ['required',  Rule::enum(AbstractsLanguage::class)],
            'max_thesis_characters' => ['required', 'int', 'min:100', 'max:20000'],
            'thesis_instruction' => ['nullable', 'string', 'max:3000'],
            'thesis_accept_until' => ['required', 'date'],
            'thesis_edit_until' => ['required', 'date', 'after_or_equal:thesis_accept_until'],
            'assets_load_until' => ['required', 'date', 'after_or_equal:thesis_edit_until'],
        ];
    }

    public function search(): array
    {
        return ['id', 'title_ru', 'title_en'];
    }

    public function getActiveActions(): array
    {
        return ['update', 'delete', 'create'];
    }
}
