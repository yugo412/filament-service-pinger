<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Forms;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Enums\AuthType;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Enums\HttpMethod;

class ServiceForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('service-pinger::service-pinger.fields.name'))
                ->required()
                ->maxLength(250),

            TextInput::make('url')

                ->label(__('service-pinger::service-pinger.fields.url'))
                ->required()
                ->url(),

            Grid::make(2)
                ->schema([
                    Select::make('method')
                        ->options(HttpMethod::class)
                        ->required(),

                    TextInput::make('expected_status')
                        ->label(__('service-pinger::service-pinger.fields.expected_status'))
                        ->default(200)
                        ->numeric()
                        ->required(),
                ]),

            Grid::make(2)
                ->schema([
                    TextInput::make('interval')
                        ->label(__('service-pinger::service-pinger.fields.interval'))
                        ->hint('In seconds')
                        ->numeric()
                        ->required()
                        ->minValue(60)
                        ->default(60),

                    TextInput::make('timeout')
                        ->label(__('service-pinger::service-pinger.fields.timeout'))
                        ->hint('In seconds')
                        ->default(30)
                        ->numeric()
                        ->required(),
                ]),

            Toggle::make('payload.skip_check_history')
                ->label(__('service-pinger::service-pinger.fields.do_not_store_check'))
                ->helperText(__('service-pinger::service-pinger.fields.do_not_store_check_helper'))
                ->live()
                ->default(false),

            Fieldset::make(__('service-pinger::service-pinger.fields.request_payload'))
                ->disabled(fn (Get $get): bool => $get('payload.skip_check_history'))
                ->schema([
                    Toggle::make('payload.store_payload_history')
                        ->label(__('service-pinger::service-pinger.fields.store_payload_history'))
                        ->default(false),

                    Tabs::make(__('service-pinger::service-pinger.tabs.requests'))
                        ->columnSpanFull()
                        ->schema([
                            Tab::make(__('service-pinger::service-pinger.tabs.headers'))
                                ->schema([
                                    KeyValue::make('payload.headers')
                                        ->hiddenLabel()
                                        ->columnSpanFull(),
                                ]),

                            Tab::make(__('service-pinger::service-pinger.tabs.body'))
                                ->schema([
                                    KeyValue::make('payload.body')
                                        ->hiddenLabel()
                                        ->columnSpanFull(),

                                    Toggle::make('payload.body_as_json')
                                        ->label(__('service-pinger::service-pinger.fields.body_as_json')),
                                ]),

                            Tab::make(__('service-pinger::service-pinger.tabs.auth'))
                                ->schema([
                                    Radio::make('payload.auth.type')
                                        ->label(__('service-pinger::service-pinger.fields.auth_type'))
                                        ->options(AuthType::class)
                                        ->enum(AuthType::class)
                                        ->live(),

                                    TextInput::make('payload.auth.username')
                                        ->label(__('service-pinger::service-pinger.fields.username'))
                                        ->visible(fn (Get $get): bool => $get('payload.auth.type') == AuthType::Basic)
                                        ->required(fn (Get $get): bool => $get('payload.auth.type') == AuthType::Basic),

                                    TextInput::make('payload.auth.password')
                                        ->label(__('service-pinger::service-pinger.fields.password'))
                                        ->password()
                                        ->revealable()
                                        ->visible(fn (Get $get): bool => $get('payload.auth.type') == AuthType::Basic)
                                        ->required(fn (Get $get): bool => $get('payload.auth.type') == AuthType::Basic),

                                    TextInput::make('payload.auth.token')
                                        ->label(__('service-pinger::service-pinger.fields.token'))
                                        ->password()
                                        ->revealable()
                                        ->visible(fn (Get $get): bool => $get('payload.auth.type') === AuthType::Bearer)
                                        ->required(fn (Get $get): bool => $get('payload.auth.type') === AuthType::Bearer),
                                ]),
                        ]),
                ]),

        ];
    }
}
