<?php

namespace Yugo\FilamentServicePinger\Resources\ServiceResource\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum HttpMethod: string implements HasColor, HasLabel
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

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Get => Color::Blue,
            self::Post => Color::Green,
            self::Patch => Color::Orange,
            self::Put => Color::Amber,
            self::Delete => Color::Red,
            default => Color::Gray,
        };
    }
}
