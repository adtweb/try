<?php

return [
    'thesis_created_participant_notification' => [
        'subject' => 'Abstracts sending success',
        'text' => 'This is to notify that your abstract :abstract_title has been received and given the ID :abstract_id. Please use this ID in the further correspondence. Pdf copy of this abstract is attached.',
        'action' => 'Edit abstract',
        'salutation' => 'Program Committee of :conference_title',
    ],
    'thesis_created_organization_notification' => [
        'subject' => 'Abstracts were received',
        'text' => 'A new abstract entitled :abstract_title by :authors has been received by :conference_title abstract submission system at :datetime and given the ID :abstract_id. Pdf copy of this abstract is attached.',
    ],
    'thesis_deleted_participant_notification' => [
        'subject' => 'Abstracts withdrawn',
        'text' => 'This is to notify that you have withdrawn the abstract :thesis_id, :abstract_title submitted to the :conference_title. If you wish to reinstate this contribution please submit it as a new abstract.',
    ],
    'thesis_deleted_organization_notification' => [
        'subject' => 'Abstracts withdrawn',
        'text' => 'This is to notify that abstract :thesis_id, :abstract_title submitted to the :conference_title was withdrawn.',
        'action' => 'To Event',
    ],
    'schedule_change_notification' => [
        'subject' => 'Changes to the event schedule',
        'intro' => 'Please note that  the ":conference_title" program has been changed',
        'poster' => ':thesis_title, ID :thesis_id is scheduled for :date :time.',
        'oral' => ':thesis_title, ID :thesis_id is scheduled for :date :time, duration :duration minutes.',
        'btn' => 'View schedule',
    ],
    'created_as_moderator' => [
        'subject' => 'Invitation to join as a moderator',
        1 => 'You have been invited to be a moderator at the :conference_title.',
        2 => 'You can log in to the site using the following information:',
        3 => 'email: :email',
        4 => 'Password: :password',
        'btn' => 'Go to event',
    ],
    'invited_as_moderator' => [
        'subject' => 'Invitation to join as a moderator',
        1 => 'You have been invited to be a moderator at the event :conference_title.',
        'btn' => 'Go to event',
    ],
    'thesis_asset_created_notification' => [
        'subject' => 'A new file has been uploaded to the abstract',
        1 => 'In the ":conference_title", a new file has been uploaded to the thesis :thesis_id :thesis_title.',
        'btn' => 'Open file',
    ],
    'thesis_updated_by_organizer_notification' => [
        'subject' => 'Your abstract has been edited by the conference organizer',
        1 => 'The conference organizer has edited your abstract. Updated version is attached. You can edit your abstract by following the link below.',
        'btn' => 'Go to abstract',
    ],
    'thesis_updated_notification' => [
        'subject' => 'Previously submitted abstracts have been updated',
        1 => 'Previously submitted abstracts ":thesis_title", ID: :thesis_id have been edited by the author',
        'btn' => 'Open abstracts',
    ],
];
