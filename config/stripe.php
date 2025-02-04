<?php

return [
    'test' => [
        'sk' => env('STRIPE_SK'),
        'pk' => env('STRIPE_PK'),
    ],
    'live' => [
        'sk' => env('STRIPE_SK'),
        'pk' => env('STRIPE_PK'),
    ],
];
