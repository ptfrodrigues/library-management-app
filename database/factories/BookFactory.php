<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalCopies = fake()->numberBetween(1, 10);
        return [
            'title' => fake()->sentence(3),
            'genre' => fake()->word,
            'language' => fake()->languageCode,
            'isbn' => fake()->unique()->isbn13,
            'year' => fake()->year,
            'observations' => fake()->paragraph,
        ];
    }
}