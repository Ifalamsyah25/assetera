<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'code_asset' => strtoupper(fake()->unique()->bothify('AST-###??')),
            'name_asset' => fake()->words(3, true),
            'category_asset' => fake()->randomElement(['Elektronik', 'Peralatan', 'Furnitur']),
            'status_asset' => Asset::STATUS_AVAILABLE,
            'purchase_date' => fake()->date(),
            'purchase_price' => fake()->numberBetween(100000, 5000000),
        ];
    }
}
