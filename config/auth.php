<?php
return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],
    
    'providers' => [
        'users' => [
            'driver' => 'database',
            'model' => User::class,
        ],
    ],
    
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60, // minutes
        ],
    ],
    
    'session' => [
        'lifetime' => 120, // minutes
        'expire_on_close' => false,
        'cookie_name' => 'auth_session',
        'cookie_path' => '/',
        'cookie_domain' => null,
        'cookie_secure' => false,
        'cookie_httponly' => true,
    ],
]; 