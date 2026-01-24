<?php

return [
    'navigations' => [
        'service' => 'Services',
    ],

    'titles' => [
        'service' => 'Services',
        'check' => 'Service Checks',
        'view_check' => 'View Service Checks',
    ],

    'fields' => [
        'is_active' => 'Active',
        'is_up' => 'Up',
        'name' => 'Name',
        'url' => 'URL',
        'method' => 'Method',
        'interval' => 'Interval',
        'timeout' => 'Timeout',
        'status' => 'Status',
        'last_status_code' => 'Last status code',
        'last_checked_at' => 'Last checked at',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'body_as_json' => 'Send body as JSON',
        'auth_type' => 'Auth type',
        'auth_type_bearer' => 'Bearer',
        'auth_type_basic' => 'Basic',
        'username' => 'Username',
        'password' => 'Password',
        'token' => 'Token',
        'checked_at' => 'Checked at',
        'response_time' => 'Response time',
        'status_code' => 'Status code',
        'error_message' => 'Message',
        'store_payload_history' => 'Store payload in ping history',
        'expected_status' => 'Expected status',
        'no_auth' => 'No authentication',
        'raw' => 'Copyable raw',
        'ms' => ' ms',
        'no_error_message' => 'No error message',
        'do_not_store_check' => 'Do not store check history',
        'do_not_store_check_helper' => 'Run checks without saving history. Only the latest status and check time will be kept.',
        'request_payload' => 'Request Payloads',
    ],

    'tabs' => [
        'requests' => 'Requests',
        'headers' => 'Headers',
        'body' => 'Body',
        'auth' => 'Authentication',
    ],

    'tooltips' => [
        'expected_status' => 'Expected status is :status',
    ],

    'modals' => [
        'ping_now_description' => 'Are you sure you want to ping this service now?',
    ],

    'actions' => [
        'ping_now' => 'Ping',
        'view_check' => 'View checks',
    ],

    'notifications' => [
        'ping_dispatched_title' => 'Ping dispatched',
    ],

    'widgets' => [
        'total_service' => 'Total Services',
        'service_up' => 'Service UP',
        'service_down' => 'Service DOWN',
    ],
];
