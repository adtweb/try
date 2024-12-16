<?php

namespace Src\Domains\Messenger\Enums;

enum ChatType: string
{
    /**
     * Participant to participant
     */
    case P2P = 'p2p';
    /**
     * Participant to moderator
     */
    case P2M = 'p2m';
    /**
     * Participant to organization
     */
    case P2O = 'p2o';
}
