<?php

declare(strict_types=1);

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/functions.php';

$is_auth = (bool) rand(0, 1);

$user_name = 'Александр';

$main_content = include_template('main.php', [
    'lots' => $lots,
    'categories' => $categories,
]);

$page_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $main_content,
    'title' => 'Главная',
]);

echo $page_content;
