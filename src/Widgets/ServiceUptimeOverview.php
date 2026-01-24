<?php

namespace Yugo\FilamentServicePinger\Widgets;

use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class ServiceUptimeOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $model = ModelResolver::service();

        $total = $model::count();
        $up = $model::where('is_up', true)->count();
        $down = $model::where('is_up', false)->count();

        return [
            Stat::make(__('service-pinger::service-pinger.widgets.total_service'), $total),

            Stat::make(__('service-pinger::service-pinger.widgets.service_down'), $up)
                ->color('success')
                ->icon(Heroicon::OutlinedCheckCircle),

            Stat::make(__('service-pinger::service-pinger.widgets.service_down'), $down)
                ->color($down > 0 ? 'danger' : 'gray')
                ->icon(Heroicon::OutlinedXCircle),
        ];
    }
}
