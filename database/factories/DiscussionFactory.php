<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discussion>
 */
class DiscussionFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' =>  fake()->paragraphs(3, true),
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'is_pinned' => fake()->boolean(10), // 10% chance of being pinned
            'is_locked' => false,
            'views_count' => 0,
            'replies_count' => 0,
            'last_reply_at' => null,
        ];
    }
}
