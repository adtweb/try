<?php

namespace Src\Domains\Messenger\Enums;

enum Role: string
{
    case ORGANIZATION = 'organization';
    case PARTICIPANT = 'participant';
    case MODERATOR = 'moderator';
    case ORGANIZER = 'organizer';
}
