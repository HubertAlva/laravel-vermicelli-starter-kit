<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(6),
            'slug' => $this->faker->unique()->slug(),
            'excerpt' => $this->faker->sentence(20),
            'content' => implode("\n\n", $this->faker->paragraphs(5)),
            'published_at' => Carbon::now(),
        ];
    }
}
