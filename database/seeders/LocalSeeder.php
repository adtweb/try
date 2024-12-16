<?php

namespace Database\Seeders;

use Database\Factories\ConferenceFactory;
use Database\Factories\OrganizationFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserFactory::new()->count(10)->create();
        OrganizationFactory::new()->count(5)->create();
        ConferenceFactory::new()->count(5)->create();
    }
}
