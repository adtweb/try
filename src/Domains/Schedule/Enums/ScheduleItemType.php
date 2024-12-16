<?php

namespace Src\Domains\Schedule\Enums;

enum ScheduleItemType: string
{
    case report = 'report';
    case break = 'break';
    case custom = 'custom';
}
