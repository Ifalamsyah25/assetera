<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'asset_id' => Asset::factory(),
            'borrowed_at' => fake()->dateTimeBetween('-10 days', '-2 days'),
            'returned_at' => null,
            'cost' => fake()->numberBetween(0, 150000),
        ];
    }

    public function returned(): static
    {
        return $this->state(fn () => [
            'returned_at' => fake()->dateTimeBetween('-1 days', 'now'),
        ]);
    }
}
