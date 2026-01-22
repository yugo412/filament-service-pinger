<?php

namespace Yugo\FilamentServicePinger\Resources;

use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Actions\PingNowAction;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Actions\ViewCheckAction;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Enums\HttpMethod;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Forms\ServiceForm;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\CreateService;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\EditService;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\ListService;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Pages\ListServiceCheck;
use Yugo\FilamentServicePinger\Support\UseServiceModel;

class ServiceResource extends Resource
{
    use UseServiceModel;

    protected static bool $isScopedToTenant = false;

    public static function getSlug(?Panel $panel = null): string
    {
        return ltrim(config('service-pinger.resources.slug', 'services'));
    }

    public static function getModel(): string
    {
        return static::serviceModel();
    }

    public static function getLabel(): ?string
    {
        return __('service-pinger::service-pinger.titles.service');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return config('service-pinger.navigations.group');
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        if (filled(config('service-pinger.navigations.group'))) {
            return null;
        }

        return config('service-pinger.navigations.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return config('service-pinger.navigations.sort');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema(ServiceForm::schema()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll(function (): ?string {
                $pollInterval = config('service-pinger.poll_interval');

                if ($pollInterval > 0) {
                    return $pollInterval.'s';
                }

                return null;
            })
            ->defaultSort('name')
            ->columns([
                ToggleColumn::make('is_active')
                    ->label(__('service-pinger::service-pinger.fields.is_active'))
                    ->toggleable(),

                TextColumn::make('name')
                    ->label(__('service-pinger::service-pinger.fields.name'))
                    ->url(fn (Model $record): string => ListServiceCheck::getUrl(['id' => $record->getKey()]))
                    ->weight(FontWeight::Bold)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('method')
                    ->label(__('service-pinger::service-pinger.fields.method')),

                TextColumn::make('url')
                    ->label(__('service-pinger::service-pinger.fields.url'))
                    ->url(fn (Model $record): string => $record->url)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('last_status_code')
                    ->label(__('service-pinger::service-pinger.fields.last_status_code'))
                    ->color(fn (Model $record): string => $record->last_status_code === $record->expected_status ? 'success' : 'danger'),

                IconColumn::make('is_up')
                    ->label(__('service-pinger::service-pinger.fields.is_up'))
                    ->boolean(),

                TextColumn::make('last_checked_at')
                    ->label(__('service-pinger::service-pinger.fields.last_checked_at'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('service-pinger::service-pinger.fields.created_at'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label(__('service-pinger::service-pinger.fields.updated_at'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),

                TernaryFilter::make('is_up'),

                SelectFilter::make('method')
                    ->options(HttpMethod::class)
                    ->multiple(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                PingNowAction::make(),
                ViewCheckAction::make(),

                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),

            ])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListService::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
            'checks' => ListServiceCheck::route('/{id}/checks'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
