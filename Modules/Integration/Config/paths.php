<?php
/**
 * Пути для парсера откуда - куда скачивать файлы, нужно для разделения файлов
 */
return [
    'ftp-paths' => [
        'products' => 'import/ready/products',
        'orders'   => 'import/ready/orders',
        'users'   => 'import/ready/users',
    ],
    'local-paths' => [
        'products' => 'integration/products',
        'orders' => 'integration/orders',
        'users' => 'integration/users',
    ],
];
