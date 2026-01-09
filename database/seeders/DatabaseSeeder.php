<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Post::factory(50)->create();

        $this->call([
            UserSeeder::class,
        ]);
    }
}
