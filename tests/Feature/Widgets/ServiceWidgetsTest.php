<?php

namespace Tests\Feature\Widgets;

use Livewire\Livewire;
use Tests\TestCase;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\ListServiceCheck;
use Yugo\FilamentServicePinger\Widgets\ServiceCheckOverview;
use Yugo\FilamentServicePinger\Widgets\ServicePingOverview;

class ServiceWidgetsTest extends TestCase
{
    private function createCheck($service, array $attributes = []): void
    {
        $service->checks()->create([
            'url' => $service->url,
            'method' => 'GET',
            'is_up' => true,
            'status_code' => 200,
            'response_time' => 100,
            'checked_at' => now(),
            'payload' => [],
            ...$attributes,
        ]);
    }

    public function test_check_overview_widget_renders_history_stats(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        $this->createCheck($service);
        $this->createCheck($service);
        $this->createCheck($service, ['is_up' => false, 'status_code' => 500]);

        Livewire::test(ServiceCheckOverview::class, ['record' => $service->getKey()])
            ->assertOk()
            ->assertSee('Total Checks')
            ->assertSee('Checks UP')
            ->assertSee('Checks DOWN');
    }

    public function test_ping_overview_widget_renders_uptime_latency_and_last_check(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        $this->createCheck($service, ['response_time' => 100]);
        $this->createCheck($service, ['response_time' => 200]);
        $this->createCheck($service, ['is_up' => false, 'status_code' => 500, 'response_time' => 300]);

        Livewire::test(ServicePingOverview::class, ['record' => $service->getKey()])
            ->assertOk()
            ->assertSee('Uptime')
            ->assertSee('66.7%')
            ->assertSee('Average Response Time')
            ->assertSee('200 ms')
            ->assertSee('Last Checked');
    }

    public function test_widgets_are_registered_on_checks_page(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        $this->createCheck($service);

        Livewire::test(ListServiceCheck::class, ['record' => $service->getKey()])
            ->assertOk()
            ->assertSee('ServiceCheckOverview', stripInitialData: false)
            ->assertSee('ServicePingOverview', stripInitialData: false);
    }
}
