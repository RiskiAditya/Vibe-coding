<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'status' => fake()->randomElement(['available', 'borrowed', 'damaged', 'maintenance']),
            'image' => null,
            'description' => fake()->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the equipment is available.
     *
     * @return $this
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
        ]);
    }

    /**
     * Indicate that the equipment is borrowed.
     *
     * @return $this
     */
    public function borrowed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'borrowed',
        ]);
    }

    /**
     * Indicate that the equipment is damaged.
     *
     * @return $this
     */
    public function damaged(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'damaged',
        ]);
    }

    /**
     * Indicate that the equipment is under maintenance.
     *
     * @return $this
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }
}
