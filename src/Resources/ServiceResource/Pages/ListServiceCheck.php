<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Pages;

use Exception;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use Yugo\FilamentServicePinger\Resources\ServiceResource;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Actions\PingNowAction;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class ListServiceCheck extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = ServiceResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament-service-pinger::pages.service-checks';

    public ?object $service;

    protected function getHeaderActions(): array
    {
        return [
            PingNowAction::make(
                name: 'ping_now',
                action: function (): void {
                    $jobClass = config('service-pinger.jobs.ping');
                    if (! class_exists($jobClass)) {
                        throw new Exception("Class {$jobClass} does not exists");
                    }

                    Bus::dispatch(new $jobClass($this->service->getKey()));
                },
            ),
        ];
    }

    public function mount(int|string $id): void
    {
        $serviceModel = ModelResolver::service();

        $this->service = $serviceModel::findOrFail($id);
    }

    public function getBreadcrumbs(): array
    {
        return [
            ServiceResource::getUrl() => __('service-pinger::service-pinger.titles.service'),
            EditService::getUrl(['record' => $this->service->getKey()]) => $this->service->name,
            null => __('service-pinger::service-pinger.titles.check'),
        ];
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('service-pinger::service-pinger.titles.check');
    }

    public function getSubheading(): ?string
    {
        return $this->service->name;
    }

    public function table(Table $table): Table
    {
        $checkModel = ModelResolver::check();

        return $table
            ->poll(function (): ?string {
                $pollInterval = config('service-pinger.poll_interval');

                if ($pollInterval > 0) {
                    return $pollInterval.'s';
                }

                return null;
            })
            ->query(
                $checkModel::query()
                    ->with(['service'])
                    ->where('service_id', $this->service->getKey())
            )
            ->defaultSort('checked_at', 'desc')
            ->columns([
                IconColumn::make('is_up')
                    ->label(__('service-pinger::service-pinger.fields.is_up'))
                    ->boolean(),

                TextColumn::make('method')
                    ->label(__('service-pinger::service-pinger.fields.method'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('url')
                    ->label(__('service-pinger::service-pinger.fields.url'))
                    ->url(fn (Model $record): ?string => $record->url)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status_code')
                    ->label(__('service-pinger::service-pinger.fields.status_code'))
                    ->tooltip(function (Model $record): string {
                        return __('service-pinger::service-pinger.tooltips.expected_status', [
                            'status' => $record->service->expected_status,
                        ]);
                    })
                    ->color(fn (Model $record): string => $record->status_code === $record->service->expected_status ? 'success' : 'danger')
                    ->sortable(),

                TextColumn::make('response_time')
                    ->label(__('service-pinger::service-pinger.fields.response_time'))
                    ->suffix(' ms')
                    ->sortable(),

                TextColumn::make('checked_at')
                    ->label(__('service-pinger::service-pinger.fields.checked_at'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('error_message')
                    ->label(__('service-pinger::service-pinger.fields.error_message'))
                    ->toggleable(),

            ])
            ->defaultSort('checked_at', 'desc');
    }
}
