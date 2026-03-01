<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowDate = fake()->dateTimeBetween('-30 days', 'now');
        
        return [
            'user_id' => User::factory(),
            'equipment_id' => Equipment::factory(),
            'borrow_date' => $borrowDate,
            'requested_return_date' => fake()->dateTimeBetween($borrowDate, '+30 days'),
            'actual_return_date' => null,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'returned']),
            'approved_by' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the borrowing is pending.
     *
     * @return $this
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_by' => null,
        ]);
    }

    /**
     * Indicate that the borrowing is approved.
     *
     * @return $this
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory(),
        ]);
    }

    /**
     * Indicate that the borrowing is rejected.
     *
     * @return $this
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_by' => User::factory(),
        ]);
    }

    /**
     * Indicate that the borrowing is returned.
     *
     * @return $this
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'returned',
            'approved_by' => User::factory(),
            'actual_return_date' => fake()->dateTimeBetween($attributes['borrow_date'], 'now'),
        ]);
    }
}
