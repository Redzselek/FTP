<?php
return [
    // 'paths' => ['api/*', '/PixelArtSpotlight/*', 'sanctum/csrf-cookie'],
    // majd a '/PixelArtSpotlight/*' helyett a mi vizsgaremekünknek a link-jét kell beírni
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:4200'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];