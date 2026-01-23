<?php

namespace Yugo\FilamentServicePinger\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Yugo\FilamentServicePinger\Support\JobResolver;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class ServicePingerCommand extends Command
{
    protected $signature = 'service-pinger:run';

    protected $description = 'Dispatch ping jobs for due services';

    public function handle(): int
    {
        $serviceModel = ModelResolver::service();
        $jobClass = JobResolver::ping();

        $now = Carbon::now();

        $serviceModel::query()
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('next_check_at')
                    ->orWhere('next_check_at', '<=', $now);
            })
            ->chunkById(50, function (Collection $services) use ($jobClass): void {
                foreach ($services as $service) {
                    Bus::dispatch(new $jobClass($service->getKey()));
                }
            });

        return self::SUCCESS;
    }
}
