<?php

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Enums\HttpMethod;

class ServiceCheck extends Model
{
    protected $table = 'pinger_service_checks';

    protected $fillable = [
        'service_id',
        'url',
        'method',
        'is_up',
        'status_code',
        'response_time',
        'error_message',
        'checked_at',
        'payload',
    ];

    public function getTable()
    {
        return 'pinger_service_checks';
    }

    public function casts(): array
    {
        return [
            'checked_at' => 'datetime',
            'payload' => 'array',
            'method' => HttpMethod::class,
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
