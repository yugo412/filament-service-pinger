<?php

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Fixtures\Models\Factories\ServiceFactory;
use Yugo\FilamentServicePinger\Resources\ServiceResource\Enums\HttpMethod;

class Service extends Model
{
    use HasFactory;
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

    protected static function newFactory()
    {
        return ServiceFactory::new();
    }

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
