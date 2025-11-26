<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], //tambahan buat dashbord
    'allowed_methods' => ['*'],                     // tambahan buat dashbord
    'allowed_origins' => ['http://localhost:5173', 'http://127.0.0.1:5173', 'http://localhost:3000', 'http://127.0.0.1:3000'],
    'allowed_origins_patterns' => [], //tambahan buat dashbord
    'allowed_headers' => ['*'],
    'exposed_headers' => [],//tambahan buat dashbord
    'max_age' => 0,//tambahan buat dashbord
    'supports_credentials' => true,
];
