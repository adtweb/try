<?php

namespace Src\Domains\Messenger\Enums;

enum Chatable: string
{
    case PARTICIPANT = 'participant';
    case ORGANIZATION = 'organization';
}
