<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:4200'], // Angular fejlesztői szerver LOCALBAN FUT AZ ANGULAR
    'allowed_origins' => ['egyedirobi.moriczcloud.hu'], // A szerver domain, vagy relatív URL: '', címe MÁR A SZERVEREN VAN
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Ez fontos!
];