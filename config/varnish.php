<?php

return [
    'host' => [env('VARNISH_HOST', 'localhost')],
    'administrative_secret' => env('VARNISH_SECRET', '/etc/varnish/secret'),
    'administrative_port' => env('VARNISH_ADMIN_PORT', 6082),
    'cache_time_in_minutes' => env('VARNISH_CACHE_MINUTES', 1440),
    'cacheable_header_name' => env('VARNISH_CACHE_HEADER', 'X-Cacheable'),
];
