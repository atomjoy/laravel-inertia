<?php

namespace Database\Seeders\Auth;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Auth\Users\AdminSeeder;
use Database\Seeders\Auth\Users\WorkerSeeder;
use Database\Seeders\Auth\Users\WriterSeeder;
use Database\Seeders\Auth\Users\SuperAdminSeeder;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        $this->call([
            WriterSeeder::class,
        ]);

        // Admins
        $this->call([
            AdminSeeder::class,
            SuperAdminSeeder::class,
            WorkerSeeder::class,
        ]);
    }
}
