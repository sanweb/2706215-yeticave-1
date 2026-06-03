<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var bool   $is_auth */
/** @var array  $user */
/** @var array  $categories */

if (is_auth()) {
    redirect_to('/');
}

$form_data = [];
$form_errors = [];

if ($_SERVER['REQUEST_METHOD'] === HttpMethodEnum::POST->value) {
    $form_data = $_POST;

    $form_errors = validate_form_data(
        VALIDATION_RULES[CREATE_USER_FORM_KEY],
        $form_data,
        $form_errors,
        ['db' => $db_connection]
    );

    if (empty($form_errors)) {
        $data = build_create_user_form_data($form_data);

        $user_id = create_user($db_connection, $data);

        if ($user_id) {
            redirect_to('/login.php');
        }

        // TODO: Add proper error handling if user creation fails.
    }
}

$main_content = include_template('sign-up.php', [
    'categories'  => $categories,
    'form_name'   => CREATE_USER_FORM_KEY,
    'form_data'   => $form_data,
    'form_errors' => $form_errors,
]);

$page_content = include_template('layout/main.php', [
    'page_title'     => 'Регистрация',
    'is_auth'        => $is_auth,
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
