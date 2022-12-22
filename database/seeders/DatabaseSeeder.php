<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Get current environment.
        $environment = env('APP_ENV');

        // Check if environment is local or testing.
        $allowed_environments = ['local', 'test', 'dev', 'develop', 'development'];
        $isLocal = in_array($environment, $allowed_environments);

        // Seed data for all environments.
        $this->call([
            PermissionSeeder::class,
            // eg.. AdminSeeder::class
        ]);

        // Seed data only for local or testing environment.
        if ($isLocal) {
            // $this->call([]);
        }
    }
}
