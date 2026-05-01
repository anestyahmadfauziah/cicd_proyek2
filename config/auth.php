<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Guard untuk admin biasa
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard untuk superadmin
        'superadmin' => [
            'driver' => 'session',
            'provider' => 'superadmins',
        ],
    ],

    'providers' => [
        // Provider untuk admin biasa
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\AdminWisata::class,
        ],

        // Provider untuk superadmin
        'superadmins' => [
            'driver' => 'eloquent',
            'model' => App\Models\SuperAdmin::class, // model baru untuk tabel super_admin
        ],
    ],

    'passwords' => [
        // Password reset admin biasa
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // Password reset superadmin
        'superadmins' => [
            'provider' => 'superadmins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];