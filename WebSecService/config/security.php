<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Complexity Requirements
    |--------------------------------------------------------------------------
    |
    | These options define the password complexity requirements for the application.
    |
    */
    'password' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'dictionary_check' => true,
        'prevent_common_passwords' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Account Lockout
    |--------------------------------------------------------------------------
    |
    | This section controls the account lockout settings.
    |
    */
    'lockout' => [
        'enabled' => true,
        'max_attempts' => 5,
        'decay_minutes' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Options for two-factor authentication.
    |
    */
    '2fa' => [
        'enabled' => true,
        'force_for_admins' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | CSP directives.
    |
    */
    'csp' => [
        'enable' => true,
        'report_only' => false,
        'report_uri' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for various routes.
    |
    */
    'rate_limiting' => [
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'login' => [
            'max_attempts' => 5,
            'decay_minutes' => 1,
        ],
        'register' => [
            'max_attempts' => 3,
            'decay_minutes' => 5,
        ],
        'password_reset' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
        ],
    ],
];
