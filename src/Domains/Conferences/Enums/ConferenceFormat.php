<?php

namespace Src\Domains\Conferences\Enums;

use App\Traits\ReturnsValues;

enum ConferenceFormat: string
{
    use ReturnsValues;

    case national = 'national';
    case international = 'international';

    public function toString(): string
    {
        return match ($this) {
            self::national => 'Российское/Национальное',
            self::international => 'Международное',
        };
    }
}
