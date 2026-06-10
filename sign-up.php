<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

if (is_auth()) {
    redirect_to('/');
}

$form_data = [];
$form_errors = [];

if (is_post_request()) {
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
            // Redirect registered user
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
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
