<?php

namespace Src\Domains\Conferences\ValueObjects;

use JsonSerializable;

class Phone implements JsonSerializable
{
    public function __construct(private string $phone) {}

    public function raw(): string
    {
        return $this->phone;
    }

    public function clean(): string
    {
        return str($this->phone)
            ->replaceMatches('~\D~', '')
            ->replaceStart('8', '+7')
            ->value();
    }

    public function jsonSerialize(): array
    {
        return [
            'raw' => $this->raw(),
            'clean' => $this->clean(),
        ];
    }

    public function __toString(): string
    {
        return $this->raw();
    }
}
