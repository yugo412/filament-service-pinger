<?php

namespace Yugo\FilamentServicePinger\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yugo\FilamentServicePinger\Contracts\Pinger;
use Yugo\FilamentServicePinger\Events\ServiceChecked;
use Yugo\FilamentServicePinger\Events\ServiceRecovered;
use Yugo\FilamentServicePinger\Events\ServiceWentDown;

class PingServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly int|string $serviceId) {}

    public function middleware(): array
    {
        return [
            new WithoutOverlapping("service-ping-{$this->serviceId}"),
        ];
    }

    public function handle(Pinger $pinger): void
    {
        $serviceModel = config('service-pinger.models.service');
        $checkModel = config('service-pinger.models.check');

        $service = $serviceModel::query()->find($this->serviceId);

        if (empty($service) || ! $service->is_active) {
            return;
        }

        $wasUp = $service->is_up;

        $ping = $pinger->ping($service);

        DB::transaction(function () use ($checkModel, $service, $ping, $wasUp): void {
            $now = Carbon::now();

            $check = $checkModel::create([
                'service_id' => $service->getKey(),
                'url' => $service->url,
                'method' => $service->method,
                'is_up' => $ping->isUp,
                'status_code' => $ping->statusCode,
                'response_time' => $ping->responseTimeMs,
                'error_message' => $ping->error,
                'checked_at' => $now,
                'payload' => data_get($service->payload, 'store_payload_history', false)
                    ? Arr::except($service->payload, 'store_payload_history')
                    : [],
            ]);

            $service->update([
                'is_up' => $ping->isUp,
                'last_status_code' => $ping->statusCode,
                'last_response_time' => $ping->responseTimeMs,
                'last_checked_at' => $now,
                'next_check_at' => Carbon::now()->addSeconds($service->interval),
            ]);

            event(new ServiceChecked($service, $check, $ping->isUp));

            if ($wasUp && ! $ping->isUp) {
                event(new ServiceWentDown($service, $check));
            }

            if (! $wasUp && $ping->isUp) {
                event(new ServiceRecovered($service, $check));
            }
        });
    }
}
