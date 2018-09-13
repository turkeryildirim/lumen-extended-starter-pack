<?php

return [
    'api' => [
        'title' => 'My API',
    ],
    'routes' => [
        'api' => '/api/documentation',
        'docs' => '/docs',
        'oauth2_callback' => '/api/oauth2-callback',
        'assets' => '/swagger-ui-assets',
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],
    'paths' => [
        'docs' => storage_path('api-docs'),
        'docs_json' => 'api-docs.json',
        'annotations' => base_path('app'),
        'excludes' => [],
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'views' => base_path('resources/views/vendor/swagger-lume'),
    ],
    'security' => [
        'Authorization' => [
            'type' => 'apiKey',
            'description' => 'User token to be used to access API end points',
            'name' => 'Authorization',
            'in' => 'header',
        ],
        /*
        |--------------------------------------------------------------------------
        | Examples of Security definitions
        |--------------------------------------------------------------------------
        */
        /*
        'api_key_security_example' => [ // Unique name of security
            'type' => 'apiKey', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2".
            'description' => 'A short description for security scheme',
            'name' => 'api_key', // The name of the header or query parameter to be used.
            'in' => 'header', // The location of the API key. Valid values are "query" or "header".
        ],
        'oauth2_security_example' => [ // Unique name of security
            'type' => 'oauth2', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2".
            'description' => 'A short description for oauth2 security scheme.',
            'flow' => 'implicit', // The flow used by the OAuth2 security scheme. Valid values are "implicit", "password", "application" or "accessCode".
            'authorizationUrl' => 'http://example.com/auth', // The authorization URL to be used for (implicit/accessCode)
            //'tokenUrl' => 'http://example.com/auth' // The authorization URL to be used for (password/application/accessCode)
            'scopes' => [
                'read:projects' => 'read your projects',
                'write:projects' => 'modify projects in your account',
            ]
        ],*/

        /* Open API 3.0 support
        'passport' => [ // Unique name of security
            'type' => 'oauth2', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2".
            'description' => 'Laravel passport oauth2 security.',
            'in' => 'header',
            'scheme' => 'https',
            'flows' => [
                "password" => [
                    "authorizationUrl" => config('app.url') . '/oauth/authorize',
                    "tokenUrl" => config('app.url') . '/oauth/token',
                    "refreshUrl" => config('app.url') . '/token/refresh',
                    "scopes" => []
                ],
            ],
        ],
        */
    ],
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', false),
    'swagger_version' => env('SWAGGER_VERSION', '3.0'),
    'proxy' => false,
    'additional_config_url' => null,
    'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
    'validator_url' => null,
    'constants' => [
        // 'SWAGGER_LUME_CONST_HOST' => env('SWAGGER_LUME_CONST_HOST', 'http://my-default-host.com'),
    ],
];
