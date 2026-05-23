<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var bool $is_auth */
/** @var array $user */
/** @var array $categories */

$lots = get_recent_lots($db_connection);

$main_content = include_template('404.php', [
    'categories' => $categories,
]);

$page_content = include_template('layout/main.php', [
    'page_title'     => '404 Страница не найдена',
    'is_auth'        => $is_auth,
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
