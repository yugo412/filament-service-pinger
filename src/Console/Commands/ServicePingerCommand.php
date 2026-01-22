<?php

namespace Yugo\FilamentServicePinger\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

class ServicePingerCommand extends Command
{
    protected $signature = 'service-pinger:run';

    protected $description = 'Dispatch ping jobs for due services';

    public function handle(): int
    {
        $serviceModel = config('service-pinger.models.service');
        $jobClass = config('service-pinger.jobs.ping');

        if (! class_exists($jobClass)) {
            throw new Exception("Class ${jobClass} does not exists");
        }

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
