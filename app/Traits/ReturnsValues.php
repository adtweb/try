<?php

namespace App\Traits;

trait ReturnsValues
{
    public static function values(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $result[] = $case->value;
        }

        return $result;
    }
}
