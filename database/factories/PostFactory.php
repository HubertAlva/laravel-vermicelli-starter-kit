<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(6),
            // Set explicitly: seeders may run without model events (and HasSlug)...
            'slug' => fn (array $attributes): string => Str::slug($attributes['name']),
            'excerpt' => fake()->sentence(20),
            'content' => fake()->paragraphs(5, true),
            'published_at' => now(),
        ];
    }

    /**
     * Indicate that the post is a draft.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }
}
