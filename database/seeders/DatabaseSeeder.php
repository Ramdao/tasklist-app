<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create(); 

        // Create a single user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 20 fake tasks
        for ($i = 0; $i < 20; $i++) {
            Task::create([
                'title' => $faker->randomElement(['Meeting', 'Audit', 'Customer']),
                'Assigned By' => $faker->randomElement(['Billing Manager', 'Moderator', 'Compliance Manager', 'Admin']),
                'Assigned To' => $faker->name(),
                'Priority' => $faker->randomElement(['High', 'Medium', 'Low']),
                'Status' => $faker->randomElement(['To Do', 'In Progress', 'Completed', 'Pending', 'In Review']),
                'Due Date' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            ]);
        }
    }
}
