<?php

namespace Src\Domains\Conferences\Enums;

use JsonSerializable;

enum ThesisAssetTitle: string implements JsonSerializable
{
    case presentation = 'presentation';
    case poster = 'poster';

    public function getName(): string
    {
        return ucfirst($this->value);
    }

    public function jsonSerialize(): string
    {
        return $this->getName();
    }
}
