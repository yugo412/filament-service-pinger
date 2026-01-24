<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ServicePingerCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(now());
    }

    public function test_it_dispatches_jobs_for_due_active_services(): void
    {
        Bus::fake();

        // due (null)
        $activeService = $this->serviceModelResolver::factory()
            ->create();

        // // due (<= now)
        $dueService = $this->serviceModelResolver::factory()
            ->due()
            ->create();

        // not due
        $this->serviceModelResolver::factory()
            ->notDue()
            ->create();

        // inactive
        $this->serviceModelResolver::factory()->create([
            'is_active' => false,
            'next_check_at' => null,
        ]);

        $this->artisan('service-pinger:run')
            ->assertExitCode(0);

        Bus::assertDispatched($this->pingJobResolver, 2);

        Bus::assertDispatched(
            $this->pingJobResolver,
            fn ($job) => in_array($job->serviceId, [
                $activeService->getKey(),
                $dueService->getKey(),
            ])
        );
    }
}
