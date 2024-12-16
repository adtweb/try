Новая организация:<br><br><br>


Название на русском: {{ $organization->full_name_ru }}<br>
Сокращенное название на русском: {{ $organization->short_name_ru }}<br><br>

Название на английском: {{ $organization->full_name_en }}<br>
Сокращенное название на английском: {{ $organization->short_name_en }}<br><br>

ИНН: {{ $organization->inn }}<br>
Адрес: {{ $organization->address }}<br>
Телефон: {{ $organization->phone }}<br>
WhatsApp: {{ $organization->whatsapp }}<br>
Telegram: {{ $organization->telegram }}<br>
VK: {{ $organization->vk }}<br><br>

Тип: {{ $organization->type }}<br>

@if (!empty($organization->actions))
Деятельность: {{ implode(', ', json_decode($organization->actions, true)) }}<br>
@endif
