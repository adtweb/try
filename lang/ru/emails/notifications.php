<?php

return [
    'thesis_created_participant_notification' => [
        'subject' => 'Тезисы получены',
        'text' => 'Это уведомление о том, что ваши тезисы :abstract_title получены и им присвоен ID :abstract_id. Пожалуйста, используйте этот идентификатор в дальнейшей переписке. Копия данного тезиса в формате PDF прилагается.',
        'action' => 'Редактировать тезисы',
        'salutation' => 'Программный комитет. :conference_title',
    ],
    'thesis_created_organization_notification' => [
        'subject' => 'Получены новые тезисы',
        'text' => 'Новые тезисы с заголовком :abstract_title, авторством :authors были получены на мероприятие :conference_title системой подачи тезисов :datetime. Им присвоен идентификатор :abstract_id. Копия данного тезиса в формате PDF прилагается.',
    ],
    'thesis_deleted_participant_notification' => [
        'subject' => 'Тезисы отозваны',
        'text' => 'Вы отозвали тезисы :thesis_id :abstract_title c мероприятия :conference_title. Чтобы вернуть тезисы просто пришлите нам новые.',
    ],
    'thesis_deleted_organization_notification' => [
        'subject' => 'Тезисы отозваны',
        'text' => 'Отозваны тезисы :thesis_id, :abstract_title с мероприятия :conference_title.',
        'action' => 'К мероприятию',
    ],
    'schedule_change_notification' => [
        'subject' => 'Изменения в расписании мероприятия',
        'intro' => 'Обращаем ваше внимание, что в программе конференции ":conference_title" произошли изменения',
        'poster' => ':thesis_title, ID :thesis_id запланирован на :date :time.',
        'oral' => ':thesis_title, ID :thesis_id запланирован на :date :time, продолжительность :duration минут.',
        'btn' => 'Посмотреть расписание',
    ],
    'created_as_moderator' => [
        'subject' => 'Приглашение быть модератором',
        1 => 'Вас прегласили быть модератором на мероприятии :conference_title.',
        2 => 'Войти на сайт можно используя следующие данные:',
        3 => 'email: :email',
        4 => 'Пароль: :password',
        'btn' => 'Перейти к мероприятию',
    ],
    'invited_as_moderator' => [
        'subject' => 'Приглашение быть модератором',
        1 => 'Вас прегласили быть модератором на мероприятии :conference_title.',
        'btn' => 'Перейти к мероприятию',
    ],
    'thesis_asset_created_notification' => [
        'subject' => 'К тезису загружен новый файл',
        1 => 'В конференции ":conference_title", к тезису :thesis_id :thesis_title загружен новый файл.',
        'btn' => 'Открыть файл',
    ],
    'thesis_updated_by_organizer_notification' => [
        'subject' => 'Ваши тезисы изменены организатором конференции',
        1 => 'Организатор конференции изменил Ваши тезисы. Обновленный вариант во вложении. Вы можете изменить тезисы, перейдя по ссылке ниже.',
        'btn' => 'Перейти к тезисам',
    ],
    'thesis_updated_notification' => [
        'subject' => 'Поданые ранее тезисы обновлены',
        1 => 'Поданные ранее тезисы ":thesis_title", ID: :thesis_id были изменены автором',
        'btn' => 'Открыть тезисы',
    ],
];
