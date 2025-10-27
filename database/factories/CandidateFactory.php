<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'bio' => fake()->paragraph(3),
            'position' => fake()->randomElement(['President', 'Vice President', 'Secretary', 'Treasurer']),
            'photo' => null,
            'department' => fake()->randomElement(['Computer Science', 'Mathematics', 'Physics', 'Chemistry', 'English']),
            'year' => fake()->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
            'manifesto' => fake()->paragraphs(5, true),
            'is_active' => true,
        ];
    }
}
