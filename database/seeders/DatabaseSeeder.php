<?php

namespace Database\Seeders;

use App\Models\Trip;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create trips for the user
        $trips = Trip::factory(2)->create([
            'user_id' => $user->id
        ]);

        // Create categories
        $categories = Category::factory(5)->create();

        // Attach random categories to each trip
        $trips->each(function($trip) use ($categories) {

            $trip->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
