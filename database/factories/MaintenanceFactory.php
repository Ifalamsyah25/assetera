<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Maintenance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Maintenance>
 */
class MaintenanceFactory extends Factory
{
    protected $model = Maintenance::class;

    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'repair_description' => fake()->sentence(),
            'cost' => fake()->numberBetween(0, 500000),
            'status' => fake()->randomElement(Maintenance::statuses()),
        ];
    }
}
