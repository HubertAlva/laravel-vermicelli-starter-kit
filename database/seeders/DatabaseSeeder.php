<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionRoleSeeder::class);

        if (app()->isLocal()) {
            User::factory()->admin()->create([
                'name' => 'Administrador',
                'email' => 'admin@example.com',
            ])->lockModel('Main administrator');

            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

            Post::factory(30)->create();
            Post::factory(5)->unpublished()->create();
        }
    }
}
