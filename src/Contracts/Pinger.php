<?php

namespace Yugo\FilamentServicePinger\Contracts;

interface Pinger
{
    public function ping(object $service): PingResult;
}
