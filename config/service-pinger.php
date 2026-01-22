<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Model Bindings
    |--------------------------------------------------------------------------
    |
    | These models are used internally by the Service Pinger.
    | You may override them to use your own models, as long as
    | they extend the base models provided by the package.
    |
    */

    'models' => [

        /*
         * The Service model represents a monitored endpoint.
         */
        'service' => \Yugo\FilamentServicePinger\Models\Service::class,

        /*
         * The ServiceCheck model stores the result of each ping.
         */
        'check' => \Yugo\FilamentServicePinger\Models\ServiceCheck::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Job Configuration
    |--------------------------------------------------------------------------
    |
    | These jobs are responsible for executing service checks.
    | You may replace the ping job with your own implementation
    | to customize how services are checked.
    |
    */

    'jobs' => [

        /*
         * The job that performs the actual service ping.
         */
        'ping' => \Yugo\FilamentServicePinger\Jobs\PingServiceJob::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Polling Interval
    |--------------------------------------------------------------------------
    |
    | Determines how often (in seconds) the Filament UI should
    | refresh service status and check logs automatically.
    |
    | Set this to null or 0 to disable automatic polling.
    |
    */

    'poll_interval' => 10,

    /*
    |--------------------------------------------------------------------------
    | Filament Resource Base Slug
    |--------------------------------------------------------------------------
    |
    | This slug will be used as the base path for all resources
    | provided by the Service Pinger plugin.
    |
    | It is recommended to keep this value unique to avoid
    | route conflicts with existing Filament resources.
    |
    | Example:
    | - "/services"   → services, services/{record}, services/{record}/checks
    | - "/monitoring" → monitoring, monitoring/{record}, etc.
    |
    */

    'resources' => [
        'slug' => '/services',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Navigation
    |--------------------------------------------------------------------------
    |
    | Configuration for how Service Pinger resources appear
    | in the Filament navigation menu.
    |
    */

    'navigations' => [

        /*
         * Navigation group / parent menu.
         * Example: "Monitoring", "Operations", etc.
         */
        'group' => null,

        /*
         * Navigation sort order.
         */
        'sort' => 50,

        /*
         * Navigation icon (Heroicons).
         */
        'icon' => 'heroicon-o-signal',
    ],

];
