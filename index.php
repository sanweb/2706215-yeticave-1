<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/getwinner.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

$lots = get_recent_lots($db_connection);

$main_content = include_template('main.php', compact(
    'categories',
    'lots'
));

$page_content = include_template('layout/main.php', [
    'page_title'     => 'Главная',
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => 'container',
]);

echo $page_content;
