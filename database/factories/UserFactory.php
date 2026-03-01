<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // password
            'role' => fake()->randomElement(['admin', 'staff', 'borrower']),
        ];
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return $this
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a staff member.
     *
     * @return $this
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'staff',
        ]);
    }

    /**
     * Indicate that the user is a borrower.
     *
     * @return $this
     */
    public function borrower(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'borrower',
        ]);
    }
}
