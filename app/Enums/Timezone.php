<?php

namespace App\Enums;

enum Timezone: string
{
    case UTC_2 = 'Europe/Kaliningrad';
    case UTC_3 = 'Europe/Moscow';
    case UTC_4 = 'Europe/Samara';
    case UTC_5 = 'Asia/Yekaterinburg';
    case UTC_6 = 'Asia/Omsk';
    case UTC_7 = 'Asia/Krasnoyarsk';
    case UTC_8 = 'Asia/Irkutsk';
    case UTC_9 = 'Asia/Yakutsk';
    case UTC_10 = 'Asia/Vladivostok';
    case UTC_11 = 'Asia/Magadan';
    case UTC_12 = 'Asia/Anadyr';

    public static function all(): array
    {
        return [
            self::UTC_2->value => __('classes.enums.timezone.utc+2'),
            self::UTC_3->value => __('classes.enums.timezone.utc+3'),
            self::UTC_4->value => __('classes.enums.timezone.utc+4'),
            self::UTC_5->value => __('classes.enums.timezone.utc+5'),
            self::UTC_6->value => __('classes.enums.timezone.utc+6'),
            self::UTC_7->value => __('classes.enums.timezone.utc+7'),
            self::UTC_8->value => __('classes.enums.timezone.utc+8'),
            self::UTC_9->value => __('classes.enums.timezone.utc+9'),
            self::UTC_10->value => __('classes.enums.timezone.utc+10'),
            self::UTC_11->value => __('classes.enums.timezone.utc+11'),
            self::UTC_12->value => __('classes.enums.timezone.utc+12'),
        ];
    }

    public function toString()
    {
        return self::all()[$this->value];
    }
}
