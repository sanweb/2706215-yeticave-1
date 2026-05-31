<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var bool   $is_auth */
/** @var array  $user */
/** @var array  $categories */

$form_data = [];
$form_errors = [];

if ($_SERVER['REQUEST_METHOD'] === HttpMethodEnum::POST->value) {
    $form_data = $_POST;

    $form_errors = validate_form_data(
        VALIDATION_RULES[LOGIN_USER_FORM_KEY],
        $form_data,
        $form_errors,
        ['db' => $db_connection]
    );

    if (empty($form_errors)) {
        $email = $form_data['email'] ?? '';
        $password = $form_data['password'] ?? '';

        $user = get_user_by_email($db_connection, $email);

        if (!empty($user) && authenticate_user($user, $password)) {
            // authorized
            redirect_to('/');
        }

        // TODO: Add proper error handling if user auth fails.
    }
}

$main_content = include_template('login.php', [
    'categories'  => $categories,
    'form_name'   => LOGIN_USER_FORM_KEY,
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
