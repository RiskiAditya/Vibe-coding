<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = [
            'login',
            'logout',
            'user_created',
            'user_updated',
            'user_deleted',
            'equipment_created',
            'equipment_updated',
            'equipment_deleted',
            'category_created',
            'category_updated',
            'category_deleted',
            'borrowing_created',
            'borrowing_approved',
            'borrowing_rejected',
            'return_approved',
        ];

        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'details' => fake()->optional()->sentence(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
