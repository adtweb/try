<?php

namespace Src\Domains\Conferences\Enums;

use App\Traits\ReturnsValues;

enum ThesisReportForm: string
{
    use ReturnsValues;

    case oral = 'oral';
    case stand = 'stand';
    case any = 'any';

    public function toString(): string
    {
        return match ($this) {
            self::oral => __('classes.enums.thesis_report_form.oral'),
            self::stand => __('classes.enums.thesis_report_form.stand'),
            self::any => __('classes.enums.thesis_report_form.mixed'),
        };
    }
}
