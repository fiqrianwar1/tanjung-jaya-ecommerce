<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
     * Status memakai format yang dikenali aplikasi (pending/processing/
     * shipped/completed), bukan label bahasa Indonesia.
     */
    private const STATUSES = ['pending', 'processing', 'shipped', 'completed'];

    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-3 months', 'now');
        $status = fake()->randomElement(self::STATUSES);
        $shippedAt = in_array($status, ['shipped', 'completed'], true)
            ? (clone $createdAt)->modify('+'.rand(1, 48).' hours')
            : null;

        return [
            'user_id' => User::where('role', 'Customer')->inRandomOrder()->first()->id ?? User::factory(),
            'total' => 0,
            'shipping_cost' => fake()->randomElement([15000, 20000, 50000]),
            'courier' => fake()->randomElement(['JNE', 'JNT', 'Sicepat', 'Anteraja']),
            'resi' => $shippedAt ? strtoupper(fake()->bothify('TJ-####-????')) : null,
            'status' => $status,
            'shipped_at' => $shippedAt,
            'created_at' => $createdAt,
            'updated_at' => $shippedAt ?? $createdAt,
        ];
    }
}
