<?php

namespace Yugo\FilamentServicePinger\Widgets;

use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class ServicePingOverview extends StatsOverviewWidget
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
        $uptime = $total > 0 ? round(($up / $total) * 100, 1) : 0;

        $latest = (clone $query)->latest('checked_at')->first();
        $avgResponseTime = (clone $query)->whereNotNull('response_time')->avg('response_time');

        return [
            Stat::make(__('service-pinger::service-pinger.widgets.uptime'), $uptime.'%')
                ->description(__('service-pinger::service-pinger.widgets.uptime_description', [
                    'count' => $total,
                ]))
                ->color($total === 0 ? 'gray' : ($uptime >= 99 ? 'success' : ($uptime >= 90 ? 'warning' : 'danger')))
                ->icon(Heroicon::OutlinedArrowTrendingUp),

            Stat::make(__('service-pinger::service-pinger.widgets.avg_response_time'), $avgResponseTime !== null ? round($avgResponseTime).__('service-pinger::service-pinger.fields.ms') : '-')
                ->description(
                    $latest?->response_time !== null
                        ? __('service-pinger::service-pinger.widgets.latest_response_time', [
                            'time' => round($latest->response_time),
                        ])
                        : null
                )
                ->icon(Heroicon::OutlinedChartBar),

            Stat::make(__('service-pinger::service-pinger.widgets.last_checked'), $latest?->checked_at?->diffForHumans() ?? '-')
                ->description($latest ? __('service-pinger::service-pinger.widgets.status_'.($latest->is_up ? 'up' : 'down')) : null)
                ->color($latest ? ($latest->is_up ? 'success' : 'danger') : 'gray')
                ->icon(Heroicon::OutlinedClock),
        ];
    }
}
