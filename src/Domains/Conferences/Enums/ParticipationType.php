<?php

namespace Src\Domains\Conferences\Enums;

enum ParticipationType: string
{
    case visitor = 'visitor';
    case speaker = 'speaker';
    case special = 'special';
}
