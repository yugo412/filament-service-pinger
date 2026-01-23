<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\CodeEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Yugo\FilamentServicePinger\Resources\ServiceResource;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class ViewServiceCheck extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected static string $resource = ServiceResource::class;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament-service-pinger::pages.view-service-checks';

    public ?object $check;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->record($this->check)
                ->successRedirectUrl(fn (Model $record): string => ListServiceCheck::getUrl(['id' => $record->service->getKey()])),
        ];
    }

    public function mount(int|string $id): void
    {
        $checkModel = ModelResolver::check();

        $this->check = $checkModel::with(['service'])->findOrFail($id);
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('service-pinger::service-pinger.titles.view_check');
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->record($this->check)
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make()
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('service.name')
                                    ->label(__('service-pinger::service-pinger.fields.name'))
                                    ->url(fn (Model $record): string => EditService::getUrl(['record' => $record->service]))
                                    ->size(TextSize::Large),

                                TextEntry::make('url')
                                    ->label(__('service-pinger::service-pinger.fields.url'))
                                    ->url(fn (Model $record): string => $record->url)
                                    ->size(TextSize::Large)
                                    ->iconPosition(IconPosition::After)
                                    ->iconColor(fn (Model $record): string => $record->is_up ? 'success' : 'danger')
                                    ->icon(fn (Model $record): Heroicon => $record->is_up ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedXCircle),

                                TextEntry::make('method')
                                    ->label(__('service-pinger::service-pinger.fields.method'))
                                    ->size(TextSize::Large)
                                    ->badge(),

                                TextEntry::make('status_code')
                                    ->label(__('service-pinger::service-pinger.fields.status_code'))
                                    ->size(TextSize::Large)
                                    ->hint(
                                        fn (Model $record): string => __('service-pinger::service-pinger.tooltips.expected_status', [
                                            'status' => $record->service->expected_status,
                                        ])
                                    ),

                                TextEntry::make('response_time')
                                    ->label(__('service-pinger::service-pinger.fields.response_time'))
                                    ->suffix(__('service-pinger::service-pinger.fields.ms'))
                                    ->size(TextSize::Large),

                                CodeEntry::make('error_message')
                                    ->label(__('service-pinger::service-pinger.fields.error_message'))
                                    ->default(__('service-pinger::service-pinger.fields.no_error_message')),

                                Tabs::make()
                                    ->schema([
                                        Tab::make(__('service-pinger::service-pinger.tabs.headers'))
                                            ->schema([
                                                KeyValueEntry::make('payload.headers')
                                                    ->hiddenLabel(),

                                                CodeEntry::make('payload.headers')
                                                    ->label(__('service-pinger::service-pinger.fields.raw'))
                                                    ->copyable(),
                                            ]),

                                        Tab::make(__('service-pinger::service-pinger.tabs.body'))
                                            ->schema([
                                                KeyValueEntry::make('payload.body')
                                                    ->hiddenLabel(),

                                                CodeEntry::make('payload.body')
                                                    ->label(__('service-pinger::service-pinger.fields.raw'))
                                                    ->copyable(),
                                            ]),

                                        Tab::make(__('service-pinger::service-pinger.tabs.auth'))
                                            ->schema([
                                                TextEntry::make('payload.auth.type')
                                                    ->label(__('service-pinger::service-pinger.fields.auth_type'))
                                                    ->default(__('service-pinger::service-pinger.fields.no_auth')),
                                            ]),
                                    ]),
                            ]),

                        Section::make()
                            ->schema([
                                TextEntry::make('checked_at')
                                    ->label(__('service-pinger::service-pinger.fields.checked_at'))
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label(__('service-pinger::service-pinger.fields.updated_at'))
                                    ->dateTime(),

                                TextEntry::make('created_at')
                                    ->label(__('service-pinger::service-pinger.fields.created_at'))
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }
}
