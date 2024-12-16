<?php

namespace Src\Domains\Conferences\ValueObjects;

use JsonSerializable;
use Src\Domains\Conferences\Enums\DiscountUnit;

readonly class Discount implements JsonSerializable
{
    public function __construct(public int $amount, public DiscountUnit $unit) {}

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'unit' => $this->unit->value,
        ];
    }
}
