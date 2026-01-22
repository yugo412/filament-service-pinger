<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Yugo\FilamentServicePinger\Resources\ServiceResource;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['next_check_at'] = Carbon::now()->addSeconds($data['interval']);

        return $data;
    }
}
