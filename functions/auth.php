<?php

declare(strict_types=1);

function authenticate_user(array $user, string $password): bool
{
    $is_authorized = false;

    if (
        !empty($user) &&
        !empty($user['password_hash']) &&
        verify_password($password, $user['password_hash'])
    ) {
        //session is already active (started from init.php)
        //session_start();

        $_SESSION['user'] = $user;
        $is_authorized = true;
    }

    return $is_authorized;
}

function generate_password_hash(string $password): ?string
{
    // Returns the hashed password, or FALSE on failure (PRIOR PHP 8.0), or null if the algorithm is invalid
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password(string $password, string $password_hash): bool
{
    return password_verify($password, $password_hash);
}

function is_auth(): bool
{
    return isset($_SESSION['user']);
}

function is_guest(): bool
{
    return empty($_SESSION['user']);
}
