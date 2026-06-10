<?php

declare(strict_types=1);

const MYSQL_TIME_ZONE = '+3:00';

// TODO: Replace exit() calls with exceptions and show errors on the error.php page.

/**
 * Creates a MySQL database connection and sets the connection charset.
 *
 * @param array{
 *     host: string,
 *     user: string,
 *     password: string,
 *     database: string,
 *     port: int
 * } $config Database connection settings.
 *
 * @return mysqli MySQL database connection.
 */
function db_connect(array $config): mysqli
{
    if (!isset($config['host'], $config['user'], $config['password'], $config['database'], $config['port'])) {
        exit('Ошибка конфигурации базы данных');
    }

    $connection = mysqli_connect(
        $config['host'],
        $config['user'],
        $config['password'],
        $config['database'],
        $config['port']
    );

    if ($connection === false) {
        exit('Ошибка подключения: ' . mysqli_connect_error());
    }

    if (!mysqli_set_charset($connection, 'utf8mb4')) {
        exit('Ошибка установки кодировки: ' . mysqli_error($connection));
    }

    if (!mysqli_query($connection, "SET time_zone = '" . MYSQL_TIME_ZONE . "'")) {
        exit('Ошибка установки часвого пояса MySQL: ' . mysqli_error($connection));
    }

    return $connection;
}

/**
 * Executes a regular SQL query and returns the result.
 */
function get_query_result(mysqli $connection, string $sql): mysqli_result
{
    $result = mysqli_query($connection, $sql);

    if ($result === false) {
        exit('Ошибка SQL-запроса: ' . mysqli_error($connection));
    }

    return $result;
}

/**
 * Prepares, binds and executes a prepared SQL statement.
 */
function execute_stmt(mysqli $connection, string $sql, string $types = '', array $params = []): mysqli_stmt
{
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt === false) {
        exit('Ошибка подготовки SQL-запроса: ' . mysqli_error($connection));
    }

    if (count($params) !== strlen($types)) {
        exit('Количество типов не совпадает с количеством параметров запроса');
    }

    if ($params !== [] && !mysqli_stmt_bind_param($stmt, $types, ...$params)) {
        exit('Ошибка привязки параметров запроса: ' . mysqli_stmt_error($stmt));
    }

    if (!mysqli_stmt_execute($stmt)) {
        exit('Ошибка выполнения SQL-запроса: ' . mysqli_stmt_error($stmt));
    }

    return $stmt;
}

/**
 * Executes a prepared SELECT query and returns the result.
 */
function get_stmt_result(mysqli $connection, string $sql, string $types = '', array $params = []): mysqli_result
{
    $stmt = execute_stmt($connection, $sql, $types, $params);

    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        exit('Ошибка получения результата SQL-запроса: ' . mysqli_stmt_error($stmt));
    }

    return $result;
}
