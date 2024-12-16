<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Src\Domains\Auth\Models\User;

class DeleteUsersWithUnconfirmedEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-users-with-unconfirmed-emails';

    public function handle()
    {
        User::where('email_verified_at', null)
            ->where('created_at', '<', now()->subMonth())
            ->delete();
    }
}
