<?php

namespace Yugo\FilamentServicePinger\Widgets;

use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class ServiceCheckOverview extends StatsOverviewWidget
{
    public int|string|null $record = null;

    protected function getPollingInterval(): ?string
    {
        $pollInterval = config('service-pinger.poll_interval');

        return $pollInterval > 0 ? $pollInterval.'s' : null;
    }

    protected function getStats(): array
    {
        if (blank($this->record)) {
            return [];
        }

        $checkModel = ModelResolver::check();

        $query = $checkModel::query()->where('service_id', $this->record);

        $total = (clone $query)->count();
        $up = (clone $query)->where('is_up', true)->count();
        $down = $total - $up;

        return [
            Stat::make(__('service-pinger::service-pinger.widgets.total_check'), $total)
                ->icon(Heroicon::OutlinedListBullet),

            Stat::make(__('service-pinger::service-pinger.widgets.check_up'), $up)
                ->color('success')
                ->icon(Heroicon::OutlinedCheckCircle),

            Stat::make(__('service-pinger::service-pinger.widgets.check_down'), $down)
                ->color($down > 0 ? 'danger' : 'gray')
                ->icon(Heroicon::OutlinedXCircle),
        ];
    }
}
