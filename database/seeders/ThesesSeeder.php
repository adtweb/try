<?php

namespace Database\Seeders;

use Database\Factories\ThesisFactory;
use Illuminate\Database\Seeder;

class ThesesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ThesisFactory::new()
            ->count(10)
            ->create();
    }
}
