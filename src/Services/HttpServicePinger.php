<?php

namespace Yugo\FilamentServicePinger\Services;

use BackedEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yugo\FilamentServicePinger\Contracts\Pinger;
use Yugo\FilamentServicePinger\Contracts\PingResult;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Enums\AuthType;

class HttpServicePinger implements Pinger
{
    public function ping(object $service): PingResult
    {
        $start = microtime(true);

        try {
            $method = $service->method instanceof BackedEnum
                ? $service->method->value
                : $service->method;

            $payload = $service->payload ?? [];

            $options = [];
            if (! empty($payload['body'])) {
                $options[($payload['body_as_json'] ?? false) ? 'json' : 'form_params'] = $payload['body'];
            }

            $request = Http::timeout($service->timeout ?? 30)
                ->withHeaders($payload['headers'] ?? []);

            if (! empty($payload['auth'])) {
                $authType = data_get($payload, 'auth.type');

                if ($authType === AuthType::Basic->value) {
                    $request->withBasicAuth(
                        username: data_get($payload, 'auth.username'),
                        password: data_get($payload, 'auth.password'),
                    );
                } elseif ($authType === AuthType::Bearer->value) {
                    $request->withToken(data_get($payload, 'auth.token'));
                }
            }

            $response = $request->send($method, $service->url, $options);

            $responseTime = (microtime(true) - $start) * 1000;

            return new PingResult(
                isUp: $response->status() === $service->expected_status,
                statusCode: $response->status(),
                responseTimeMs: $responseTime,
            );
        } catch (Throwable $e) {
            Log::debug($e->getMessage());

            return new PingResult(
                isUp: false,
                error: $e->getMessage(),
            );
        }
    }
}
