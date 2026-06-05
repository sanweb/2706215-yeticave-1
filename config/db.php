<?php

declare(strict_types=1);

const DEFAULT_MYSQL_PORT = 3306;

return [
    'host'     => getenv('DB_HOST') ?: 'db',
    'port'     => (int) (getenv('DB_PORT') ?: DEFAULT_MYSQL_PORT),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'database' => getenv('DB_NAME'),
];
