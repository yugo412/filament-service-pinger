<?php

namespace Tests\Unit\Jobs;

use Illuminate\Support\Facades\Event;
use Tests\Fixtures\Models\ServiceCheck;
use Tests\TestCase;
use Yugo\FilamentServicePinger\Contracts\Pinger;
use Yugo\FilamentServicePinger\Contracts\PingResult;
use Yugo\FilamentServicePinger\Events\ServiceChecked;
use Yugo\FilamentServicePinger\Events\ServiceRecovered;
use Yugo\FilamentServicePinger\Events\ServiceWentDown;

class PingServiceJobTest extends TestCase
{
    protected function fakePinger(array $data): void
    {
        $this->mock(Pinger::class, function ($mock) use ($data) {
            $mock->shouldReceive('ping')
                ->once()
                ->andReturn(new PingResult(
                    isUp: $data['isUp'],
                    statusCode: $data['statusCode'],
                    responseTimeMs: $data['responseTimeMs'],
                    error: $data['error'],
                ));
        });
    }

    public function test_it_skips_history_when_configured()
    {
        Event::fake();

        $service = $this->serviceModelResolver::create([
            'name' => fake()->word,
            'url' => fake()->url,
            'is_active' => true,
            'is_up' => true,
            'payload' => [
                'skip_check_history' => true,
            ],
        ]);

        $this->fakePinger([
            'isUp' => true,
            'statusCode' => 200,
            'responseTimeMs' => 120,
            'error' => null,
        ]);

        (new $this->pingJobResolver($service->id))->handle(app(Pinger::class));

        $this->assertDatabaseCount((new $this->serviceCheckModelResolver)->getTable(), 0);

        Event::assertDispatched(ServiceChecked::class, function ($event) {
            return $event->check === null;
        });
    }

    public function test_it_creates_check_when_history_enabled()
    {
        Event::fake();

        $service = $this->serviceModelResolver::create([
            'name' => fake()->word,
            'url' => fake()->url,
            'is_active' => true,
            'is_up' => true,
            'payload' => [],
        ]);

        $this->fakePinger([
            'isUp' => true,
            'statusCode' => 200,
            'responseTimeMs' => 90,
            'error' => null,
        ]);

        (new $this->pingJobResolver($service->id))->handle(app(Pinger::class));

        $this->assertDatabaseCount((new $this->serviceCheckModelResolver)->getTable(), 1);

        Event::assertDispatched(ServiceChecked::class, function ($event) {
            return $event->check instanceof ServiceCheck;
        });
    }

    public function test_it_dispatches_went_down_event()
    {
        Event::fake();

        $service = $this->serviceModelResolver::create([
            'name' => fake()->word,
            'url' => fake()->url,
            'is_active' => true,
            'is_up' => true,
            'payload' => [],
        ]);

        $this->fakePinger([
            'isUp' => false,
            'statusCode' => 500,
            'responseTimeMs' => 300,
            'error' => 'Down',
        ]);

        (new $this->pingJobResolver($service->id))->handle(app(Pinger::class));

        Event::assertDispatched(ServiceWentDown::class);
    }

    public function test_it_dispatches_recovered_event()
    {
        Event::fake();

        $service = $this->serviceModelResolver::create([
            'name' => fake()->word,
            'url' => fake()->url,
            'is_active' => true,
            'is_up' => false,
            'payload' => [],
        ]);

        $this->fakePinger([
            'isUp' => true,
            'statusCode' => 200,
            'responseTimeMs' => 80,
            'error' => null,
        ]);

        (new $this->pingJobResolver($service->id))->handle(app(Pinger::class));

        Event::assertDispatched(ServiceRecovered::class);
    }
}
