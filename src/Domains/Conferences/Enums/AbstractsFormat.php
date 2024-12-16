<?php

namespace Src\Domains\Conferences\Enums;

use App\Traits\ReturnsValues;

enum AbstractsFormat: string
{
    use ReturnsValues;

    case a4 = 'A4';
    case a5 = 'A5';

    public function toString(): string
    {
        return match ($this) {
            self::a4 => 'A4',
            self::a5 => 'A5',
        };
    }
}
