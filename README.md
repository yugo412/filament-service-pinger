# Filament Service Pinger

> **Status: Alpha (Internal Use)**  
> This package is currently under active development and used internally.  
> APIs and configurations may change without notice.

A lightweight **service monitoring plugin for Filament**, designed to periodically ping endpoints, record health checks, and provide a clean foundation for future alerting and incident management.

---

## Features

- Filament Resource for managing monitored services
- Manual ping action (Ping Now)
- Scheduler-based automated checks
- Queue-driven ping jobs
- Service check history (logs)
- Optional payload snapshot per check
- Event-driven architecture (future alert ready)
- Database-agnostic (SQLite, MySQL, PostgreSQL)
- Configurable UI polling interval
- Configurable resource slug (conflict-safe)

---

## Requirements

- PHP 8.2+
- Laravel 11+
- Filament 4.x
- Queue worker configured

---

## Installation

Install the package via Composer:

```bash
composer require yugo/filament-service-pinger
```

---

## Publish Vendor Assets

Publish the configuration and migrations:

```bash
php artisan vendor:publish --tag=service-pinger-config
php artisan vendor:publish --tag=service-pinger-migrations
```

(Optional) Publish translations if you want to override labels:

```bash
php artisan vendor:publish --tag=service-pinger-translations
```

Run migrations:

```bash
php artisan migrate
```

---

## Enable Plugin

After installing the package, you need to register the plugin in your Filament panel.

Open your Filament panel provider (for example `app/Providers/Filament/AdminPanelProvider.php`)
and add the Service Pinger plugin:

```php
use Yugo\FilamentServicePinger\ServicePingerPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            new ServicePingerPlugin(),
        ]);
}
```

---

## Configuration

The configuration file is located at:

```php
config/service-pinger.php
```

### Example Configuration

```php
<?php

return [
    'models' => [
        'service' => \Yugo\FilamentServicePinger\Models\Service::class,
        'check' => \Yugo\FilamentServicePinger\Models\ServiceCheck::class,
    ],

    'jobs' => [
        'ping' => \Yugo\FilamentServicePinger\Jobs\PingServiceJob::class,
    ],

    'poll_interval' => 10,
    
    'resources' => [
        'slug' => '/services',
    ],

    'navigations' => [
        'group' => null,
        'sort' => 50,
        'icon' => 'heroicon-o-signal',
    ],
];

```

---

## Scheduler Setup (Required)

This package does not automatically register a scheduler.

### Laravel 11+

Register the scheduler in `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('service-pinger:run')
    ->everyMinute()
    ->withoutOverlapping();
```

---

## Queue Setup

Make sure your queue worker is running:

```bash
php artisan queue:work
```

---

## License

MIT License.
