<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Carbon;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class PruneServiceChecksCommandTest extends \Tests\TestCase
{
    public function test_it_prunes_checks_older_than_the_given_days(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        $checkModel = ModelResolver::check();
        $old = $checkModel::create([
            'service_id' => $service->getKey(),
            'url' => 'https://example.com',
            'is_up' => true,
            'payload' => [],
            'checked_at' => Carbon::now()->subDays(31),
        ]);
        $recent = $checkModel::create([
            'service_id' => $service->getKey(),
            'url' => 'https://example.com',
            'is_up' => true,
            'payload' => [],
            'checked_at' => Carbon::now()->subDays(29),
        ]);

        $this->artisan('service-pinger:prune', ['days' => 30])
            ->assertExitCode(0);

        $this->assertModelMissing($old);
        $this->assertModelExists($recent);
    }

    public function test_it_rejects_an_invalid_number_of_days(): void
    {
        $this->artisan('service-pinger:prune', ['days' => 0])
            ->assertExitCode(1);
    }
}
