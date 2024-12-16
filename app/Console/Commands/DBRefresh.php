<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Auth\Models\User;

class DBRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DB refresh';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->isProduction()) {
            $this->error('Это же прод!');

            return Command::INVALID;
        }

        $this->call('migrate:fresh');

        $this->call('db:seed');

        $user = User::create([
            'email' => 'aner-anton@yandex.ru',
            'password' => bcrypt('12345678'),
        ]);

        $user->email_verified_at = today();
        $user->save();

        Organization::create([
            'user_id' => $user->id,
            'full_name_ru' => 'Организация',
            'short_name_ru' => '',
            'full_name_en' => '',
            'short_name_en' => '',
            'inn' => '12345678901',
            'address' => 'Москва, Кленовый бульвар, д. 24 кв. 48',
            'phone' => '8-912-651-04-64',
            'type' => 'Университет',
            'actions' => json_encode(['Наука']),
        ]);
    }
}
