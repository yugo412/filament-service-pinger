<?php

namespace Tests\Fixtures\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Fixtures\Models\Service;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'url' => $this->faker->url,
            'method' => 'GET',
            'expected_status' => 200,
            'timeout' => 5,
            'interval' => 60,

            'is_active' => true,
            'is_up' => false,

            'last_status_code' => null,
            'last_response_time' => null,

            'last_checked_at' => null,
            'next_check_at' => null,

            'payload' => [],
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }

    public function due(): static
    {
        return $this->state(fn (): array => [
            'next_check_at' => now()->subMinute(),
        ]);
    }

    public function notDue(): static
    {
        return $this->state(fn (): array => [
            'next_check_at' => now()->addMinute(),
        ]);
    }
}
