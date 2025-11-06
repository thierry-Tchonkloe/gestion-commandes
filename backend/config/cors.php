<?php

return[
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173'], // URL du frontend Vue
    'allowed_origins_patterns' => [],
    'exposed_headers' => [],
    'allowed_headers' => ['*'],
    'max_age' => 0,
    'supports_credentials' => true,
];
