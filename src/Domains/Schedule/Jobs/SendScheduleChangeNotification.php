<?php

namespace Src\Domains\Schedule\Jobs;

use App\Notifications\ScheduleChangeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Src\Domains\Conferences\Models\Participation;

class SendScheduleChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Collection $theses, public int $participationId) {}

    public function handle(): void
    {
        $user = Participation::find($this->participationId)->participant->user;

        $user->notify(new ScheduleChangeNotification($this->theses));
    }
}
