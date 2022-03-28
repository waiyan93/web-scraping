<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'keyword' => $this->faker->word(),
            'total_advertisers' => $this->faker->numberBetween(1, 10),
            'total_links' => $this->faker->numberBetween(1, 20),
            'search_summary' => 'About 89,700,000 results (0.41 seconds)',
            'web_content' => '<h2>This is web content.</h2>' 
        ];
    }
}
