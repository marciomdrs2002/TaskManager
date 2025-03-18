<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get all users
        $users = User::all();

        // Create tasks
        for ($i = 0; $i < 400; $i++) {
            Task::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'priority' => $faker->numberBetween(1, 3),
                'deadline' => $faker->dateTimeBetween('now', '+1 year'),
                'completed' => $faker->boolean,
                'assignee_id' => $users->random()->id,
                'owner_id' => $users->random()->id,
            ]);
        }
    }
}