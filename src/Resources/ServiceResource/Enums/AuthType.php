<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum AuthType: string implements HasLabel
{
    case Basic = 'basic';

    case Bearer = 'bearer';

    public function getLabel(): string|Htmlable|null
    {
        return __('service-pinger::service-pinger.fields.auth_type_'.$this->value);
    }
}
