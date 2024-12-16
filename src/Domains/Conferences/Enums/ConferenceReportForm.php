<?php

namespace Src\Domains\Conferences\Enums;

use App\Traits\ReturnsValues;

enum ConferenceReportForm: string
{
    use ReturnsValues;

    case oral = 'oral';
    case stand = 'stand';
    case any = 'any';

    public function toString(): string
    {
        return match ($this) {
            self::oral => __('classes.enums.conference_report_form.oral'),
            self::stand => __('classes.enums.conference_report_form.stand'),
            self::any => __('classes.enums.conference_report_form.mixed'),
        };
    }
}
