<?php

namespace Yugo\FilamentServicePinger\Contracts;

class PingResult
{
    public function __construct(
        public bool $isUp,
        public ?int $statusCode = null,
        public ?int $responseTimeMs = null,
        public ?string $error = null,
    ) {}
}
