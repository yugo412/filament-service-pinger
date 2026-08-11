<?php

namespace Tests\Feature\Resources;

use Livewire\Livewire;
use Tests\TestCase;
use Yugo\FilamentServicePinger\Resources\ServiceResource;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\ListService;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\ListServiceCheck;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\ViewServiceCheck;

class ServiceResourceTest extends TestCase
{
    public function test_it_generates_checks_page_url_with_record_parameter(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        $url = ListServiceCheck::getUrl(['record' => $service->getKey()]);

        $this->assertSame(
            "http://localhost/admin/services/{$service->getKey()}/checks",
            $url,
        );
    }

    public function test_it_generates_view_check_action_url_with_record_parameter(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        $url = ServiceResource::getUrl('checks', ['record' => $service->getKey()]);

        $this->assertStringEndsWith(
            "/admin/services/{$service->getKey()}/checks",
            $url,
        );
    }

    public function test_list_page_can_be_loaded(): void
    {
        $services = $this->serviceModelResolver::factory()->count(3)->create();

        Livewire::test(ListService::class)
            ->assertOk()
            ->assertCanSeeTableRecords($services);
    }

    public function test_checks_page_can_be_loaded(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        Livewire::test(ListServiceCheck::class, ['record' => $service->getKey()])
            ->assertOk()
            ->assertSee($service->name);
    }

    public function test_view_check_page_can_be_loaded(): void
    {
        $service = $this->serviceModelResolver::factory()->create();

        $check = $service->checks()->create([
            'url' => $service->url,
            'method' => 'GET',
            'is_up' => true,
            'status_code' => 200,
            'response_time' => 120,
            'checked_at' => now(),
            'payload' => [],
        ]);

        Livewire::test(ViewServiceCheck::class, ['record' => $check->getKey()])
            ->assertOk()
            ->assertSee($service->name);
    }
}
