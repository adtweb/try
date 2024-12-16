Новая конференция:
<br>
<br>
Название на русском: {{ $conference->title_ru }}<br>
Название на английском: {{ $conference->title_en }}<br>
Slug: {{ $conference->slug }}<br>
Тип конференции: {{ $conference->type->title_ru }}<br>
Организация: {{ $conference->organization->full_name_ru }}<br>
Дата начала: {{ $conference->start_date->translatedFormat('d M Y') }}<br>
Дата окончания: {{ $conference->end_date->translatedFormat('d M Y') }}<br>
Формат: {{ $conference->format }}<br>
@if ($conference->format == 'national')
	С иностранным участием: {{ $conference->with_foreign_participation ? 'да' : 'нет' }}<br>
@endif

@if (!empty($conference->logo))
	Логотип: {{ config('filesystems.disks.s3.base_url') . $conference->logo }}<br>
@endif

Сайт: {{ $conference->website }}<br>
Требуется сайт: {{ $conference->need_site ? 'да' : 'нет' }}<br>
Соорганизаторы: {{ implode(', ', $conference->{'co-organizers'}) }}<br>
Адрес: {{ $conference->address }}<br>
Телефон: {{ $conference->phone }}<br>
Email: {{ $conference->email }}<br>
Часовой пояс: {{ $conference->timezone }}<br>
Описание на русском: {{ $conference->description_ru }}<br>
Описание на английском: {{ $conference->description_en }}<br>
Язык: {{ $conference->lang }}
Количество участников: {{ $conference->participants_number }}<br>
Формат отчета: {{ $conference->report_form }}<br>
WhatsApp: {{ $conference->whatsapp }}<br>
Telegram: {{ $conference->telegram }}<br>
Цена участникам: {{ $conference->price_participants }}<br>
Цена посетителям: {{ $conference->price_visitors }}<br>
Цена абстрактов: {{ $conference->abstracts_price }}<br>
Формат абстрактов: {{ $conference->abstracts_format }}<br>
Язык абстрактов: {{ $conference->abstracts_lang }}<br>
Максимальное количество символов в тезисе: {{ $conference->max_thesis_characters }}<br>
Дата окончания приема тезисов: {{ $conference->thesis_accept_until->translatedFormat('d M Y') }}<br>
Дата окончания редактирования тезисов: {{ $conference->thesis_edit_until->translatedFormat('d M Y') }}<br>
Дата окончания загрузки документов: {{ $conference->assets_load_until->translatedFormat('d M Y') }}<br>

