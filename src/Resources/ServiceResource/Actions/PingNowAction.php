<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Actions;

use Error;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class PingNowAction
{
    public static function make(?string $name = 'ping_now', ?callable $action = null): Action
    {
        if (empty($action)) {
            $action = function (Model $record): void {
                $pingJobClass = config('service-pinger.jobs.ping');

                if (! class_exists($pingJobClass)) {
                    throw new Error("Class ${pingJobClass} does not exists");
                }

                Bus::dispatch(new $pingJobClass($record->getKey()));
            };
        }

        return Action::make($name)
            ->label(__('service-pinger::service-pinger.actions.ping_now'))
            ->requiresConfirmation()
            ->color('warning')
            ->icon(Heroicon::Bolt)
            ->disabled(function (?Model $record): bool {
                if (empty($record)) {
                    return false;
                }

                return ! $record->is_active;
            })
            ->modalDescription(__('service-pinger::service-pinger.modals.ping_now_description'))
            ->action($action)
            ->successNotificationTitle(__('service-pinger::service-pinger.notifications.ping_dispatched_title'));
    }
}
