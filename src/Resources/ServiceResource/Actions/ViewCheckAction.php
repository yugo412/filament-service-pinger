<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Yugo\FilamentServicePinger\Resources\ServiceResource;

class ViewCheckAction
{
    public static function make(?string $name = null): Action
    {
        return Action::make($name ?? 'checks')
            ->label(__('service-pinger::service-pinger.actions.view_check'))
            ->icon(Heroicon::Document)
            ->url(fn (Model $record): string => ServiceResource::getUrl('checks', ['id' => $record->getKey()]));
    }
}
