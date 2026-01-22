<?php

namespace Yugo\FilamentServicePinger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Enums\HttpMethod;

class Service extends Model
{
    use SoftDeletes;

    public $table = 'pinger_services';

    protected $fillable = [
        'name',
        'url',
        'method',
        'expected_status',
        'timeout',
        'interval',
        'is_active',
        'is_up',
        'last_status_code',
        'last_response_time',
        'last_checked_at',
        'next_check_at',
        'payload',
    ];

    public function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_up' => 'boolean',
            'last_checked_at' => 'datetime',
            'next_check_at' => 'datetime',
            'payload' => 'array',
            'method' => HttpMethod::class,
        ];
    }

    public function checks(): HasMany
    {
        return $this->hasMany(ServiceCheck::class);
    }
}
