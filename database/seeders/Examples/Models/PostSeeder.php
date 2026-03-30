<?php

namespace Database\Seeders\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        for ($i = 1; $i <= 20; $i++) {

            Post::factory()->create([
                'user_id' => $user->id,
                'title' => 'Post title ' . $i,
                'content' => 'Post content goes here ' . $i,
            ]);
        }

        $user = User::find(2);

        for ($i = 21; $i <= 40; $i++) {

            Post::factory()->create([
                'user_id' => $user->id,
                'title' => 'Post title ' . $i,
                'content' => 'Post content goes here ' . $i,
            ]);
        }

        for ($i = 41; $i <= 60; $i++) {

            Post::factory()->create([
                'user_id' => $user->id,
                'title' => 'Post title ' . $i,
                'content' => 'Post content goes here ' . $i,
                'published_at' => now()->addDay(61 - $i)
            ]);
        }
    }
}
