<?php

return [

    'integration' => [
        'driver' => 'local',
        'root' => storage_path('app/integration'),
    ],

    'ftp_upload' => [
        'driver' => 'local',
        'root' => storage_path('app/integration/ftp-server'),
    ],

    'ftp' => [
        'driver' => 'ftp',
        'host' => env('FTP_SERVER'),
        'username' => env('FTP_LOGIN'),
        'password' => env('FTP_PASSWORD'),
        'port' => 21,
        'timeout' => 30,
    ],

    '1c' => [
        'driver' => 'local',
        'root' => storage_path('1c'),
    ],
];
