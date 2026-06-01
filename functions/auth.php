<?php

declare(strict_types=1);

const SESSION_USER_KEY = 'user';
const SESSION_USER_ID_KEY = 'id';
const SESSION_USER_DATA_FIELDS = ['id', 'email', 'name', 'updated_at'];

function authenticate_user(array $user, string $password): bool
{
    $is_authorized = false;

    if (
        !empty($user) &&
        !empty($user['password_hash']) &&
        password_verify($password, $user['password_hash'])
    ) {
        //session is already active (started from init.php)
        //session_start();

        $session_user_data = build_session_user_data($user);
        $_SESSION[SESSION_USER_KEY] = $session_user_data;
        $is_authorized = true;
    }

    return $is_authorized;
}

function is_auth(): bool
{
    return isset($_SESSION[SESSION_USER_KEY]);
}

function get_auth_user(): ?array
{
    return $_SESSION[SESSION_USER_KEY] ?? null;
}

function get_user_id(): ?int
{
    return isset($_SESSION[SESSION_USER_KEY][SESSION_USER_ID_KEY])
        ? (int) $_SESSION[SESSION_USER_KEY][SESSION_USER_ID_KEY]
        : null;
}

function build_session_user_data(array $user): array
{
    return array_filter($user, function (string $key) {
        return in_array($key, SESSION_USER_DATA_FIELDS, true);
    }, ARRAY_FILTER_USE_KEY);
}
