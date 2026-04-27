<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'symbol' => $this->faker->unique()->lexify('????'),
            'name' => $this->faker->company() . ' Inc.',
            'exchange' => $this->faker->randomElement(['NASDAQ', 'NYSE', 'AMEX']),
            'sector' => $this->faker->randomElement([
                'Technology',
                'Healthcare',
                'Financial Services',
                'Consumer Cyclical',
                'Industrials',
                'Energy',
                'Utilities',
                'Real Estate',
                'Materials',
                'Communication Services',
                'Consumer Defensive',
            ]),
            'industry' => $this->faker->word(),
            'market_cap' => $this->faker->numberBetween(1000000000, 3000000000000),
            'is_active' => true,
        ];
    }

    public function nasdaq(): static
    {
        return $this->state(fn (array $attributes) => [
            'exchange' => 'NASDAQ',
        ]);
    }

    public function nyse(): static
    {
        return $this->state(fn (array $attributes) => [
            'exchange' => 'NYSE',
        ]);
    }

    public function technology(): static
    {
        return $this->state(fn (array $attributes) => [
            'sector' => 'Technology',
        ]);
    }
}
