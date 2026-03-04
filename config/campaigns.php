<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sistema de Campañas - Configuración
    |--------------------------------------------------------------------------
    */

    'whatsapp' => [
        'enabled' => env('WHATSAPP_ENABLED', false),
        'api_url' => env('WHATSAPP_API_URL'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
        'timeout' => env('WHATSAPP_TIMEOUT', 30),
    ],

    'confirmaciones' => [
        'token_expiration_days' => env('CONFIRMACION_TOKEN_EXPIRATION_DAYS', 30),
        'max_confirmaciones_por_cliente' => env('MAX_CONFIRMACIONES_POR_CLIENTE', 10),
    ],

    'rate_limiting' => [
        'api_general' => env('RATE_LIMIT_API_GENERAL', 60),
        'api_consultas' => env('RATE_LIMIT_API_CONSULTAS', 10),
        'api_confirmaciones' => env('RATE_LIMIT_API_CONFIRMACIONES', 5),
    ],

    'validaciones' => [
        'telefono_min_length' => 10,
        'telefono_max_length' => 15,
        'cupo_maximo_actividad' => 1000,
    ],
];
