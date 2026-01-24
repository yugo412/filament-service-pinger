<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Yugo\FilamentServicePinger\Resources\ServiceResource;
use Yugo\FilamentServicePinger\Widgets\ServiceUptimeOverview;

class ListService extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ServiceUptimeOverview::class,
        ];
    }
}
