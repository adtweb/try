<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Domains\Conferences\Models\Thesis;

class ThesisCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Thesis $thesis) {}
}
