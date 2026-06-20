<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ActivitySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(ActivitySeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call([
                StudentSeeder::class,
            ]);
    }
}
