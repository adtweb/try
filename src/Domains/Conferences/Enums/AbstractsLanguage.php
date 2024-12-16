<?php

namespace Src\Domains\Conferences\Enums;

use App\Traits\ReturnsValues;

enum AbstractsLanguage: string
{
    use ReturnsValues;

    case ru = 'ru';
    case en = 'en';

    public function toString(): string
    {
        return match ($this) {
            self::ru => 'Русский',
            self::en => 'Английский',
        };
    }
}
