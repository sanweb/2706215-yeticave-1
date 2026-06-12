<?php

declare(strict_types=1);

return [
    'host'       => getenv('SMTP_HOST') ?: '',
    'port'       => (int) (getenv('SMTP_PORT') ?: 587),
    'username'   => getenv('SMTP_USER') ?: '',
    'password'   => getenv('SMTP_PASSWORD') ?: '',
    'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
];
