<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

$lot_id = (int) ($_GET['id'] ?? 0);
$lot = $lot_id > 0 ? get_lot_by_id($db_connection, $lot_id) : null;

if ($lot === null) {
    http_response_code(HttpCodeEnum::NOT_FOUND->value);
}

$form_data = [];
$form_errors = [];

if ($_SERVER['REQUEST_METHOD'] === HttpMethodEnum::POST->value) {
    $form_data = $_POST;

    $form_errors = validate_form_data(
        VALIDATION_RULES[CREATE_BET_FORM_KEY],
        $form_data,
        $form_errors,
        ['db' => $db_connection]
    );

    if (empty($form_errors)) {
        if ($form_data['amount'] < $lot['min_bet']) {
            $form_errors['amount'] = 'Сумма ставки меньше минимальной';
        } else {
            $data = build_create_bet_form_data($form_data, $user, $lot);

            if (create_bet($db_connection, $data)) {
                redirect_to('/lot.php?id=' . $lot_id);
            }
        }

        // TODO: Add proper error handling if bet creation fails.
    }
}

$page_title = $lot ? $lot['title'] : '404 Страница не найдена';
$main_template = $lot ? 'lot.php' : '404.php';
$main_data = $lot ? [
    'lot' => $lot,
    'form_name'   => CREATE_BET_FORM_KEY,
    'form_data'   => $form_data,
    'form_errors' => $form_errors,
] : [];

$main_content = include_template($main_template, array_merge(['categories' => $categories], $main_data));

$page_content = include_template('layout/main.php', [
    'page_title'     => $page_title,
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
