<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum HttpMethod: string implements HasLabel
{
    case Get = 'GET';

    case Post = 'POST';

    case Put = 'PUT';

    case Patch = 'PATCH';

    case Delete = 'DELETE';

    case Options = 'OPTIONS';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
}
