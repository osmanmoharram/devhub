<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $middleEastLocations = [
            'Cairo, Egypt',
            'Riyadh, Saudi Arabia',
            'Dubai, UAE',
            'Doha, Qatar',
            'Kuwait City, Kuwait',
            'Manama, Bahrain',
            'Muscat, Oman',
            'Amman, Jordan',
            'Beirut, Lebanon',
            'Istanbul, Turkey',
        ];

        return [
            'bio' => fake()->paragraphs(2, true),
            'location' => fake()->randomElement($middleEastLocations),
            'avatar' => 'avatars/placeholder-'.fake()->numberBetween(1, 10).'.jpg',
            'user_id' => User::factory(),
        ];
    }
}
