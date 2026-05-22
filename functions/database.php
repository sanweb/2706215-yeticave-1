<?php

declare(strict_types=1);

// TODO: Replace exit() calls with exceptions and show errors on the error.php page.
// TODO: Keep only low-level database helpers in this file and move category/lot query functions to separate domain files.

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

    if (!mysqli_stmt_bind_param($stmt, $types, ...$params)) {
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

/**
 * Returns all lot categories.
 *
 * @param mysqli $connection MySQL database connection.
 *
 * @return array<int, array{
 *     id: string,
 *     name: string,
 *     slug: string
 * }>
 */
function get_all_categories(mysqli $connection): array
{
    $sql = 'SELECT `id`, `name`, `slug` FROM `categories`';

    $result = get_query_result($connection, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Returns recent active lots with their current price and category name.
 *
 * The current price is the highest bet amount or the start price if there are no bets.
 * The expiration date is returned in YYYY-MM-DD format.
 *
 * @param mysqli $connection MySQL database connection.
 * @param int $limit Maximum number of lots to return.
 *
 * @return array<int, array{
 *     id: string,
 *     title: string,
 *     start_price: string,
 *     image_url: string,
 *     price: string,
 *     expire_date: string,
 *     category_name: string
 * }>
 */
function get_recent_lots(mysqli $connection, int $limit = LIMIT_RECENT_LOTS): array
{
    $limit = max(1, $limit);
    $sql = <<<SQL
        SELECT
            lots.`id`,
            lots.`title`,
            lots.`start_price`,
            lots.`image_url`,
            IFNULL(lot_bets.`max_amount`, lots.`start_price`) AS `price`,
            DATE_FORMAT(lots.`expire_date`, '%Y-%m-%d') AS `expire_date`,
            categories.`name` AS `category_name`
        FROM `lots`
            JOIN `categories` ON lots.`category_id` = categories.`id`
            LEFT JOIN (
                SELECT `lot_id`, MAX(`amount`) AS `max_amount`
                FROM `bets`
                GROUP BY `lot_id`
            ) AS lot_bets ON lot_bets.`lot_id` = lots.`id`
        WHERE lots.`expire_date` > CURRENT_DATE
        ORDER BY lots.`created_at` DESC
        LIMIT ?
    SQL;

    $result = get_stmt_result($connection, $sql, 'i', [$limit]);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param mysqli $connection
 * @param int $id
 *
 * @return array|null
 */
function get_lot_by_id(mysqli $connection, int $id): ?array
{
    $sql = <<<SQL
        SELECT
            lots.`id`,
            lots.`title`,
            lots.`description`,
            lots.`image_url`,
            lots.`start_price`,
            IFNULL(MAX(bets.`amount`), lots.`start_price`) AS `price`,
            lots.`bet_step`,
            IFNULL(MAX(bets.`amount`), lots.`start_price`) + lots.`bet_step` AS `min_bet`,
            DATE_FORMAT(lots.`expire_date`, '%Y-%m-%d') AS `expire_date`,
            categories.`name` AS `category_name`
        FROM `lots`
            JOIN `categories` ON lots.`category_id` = categories.`id`
            LEFT JOIN `bets` ON bets.`lot_id` = lots.`id`
        WHERE lots.`id` = ?
        GROUP BY lots.`id`
    SQL;

    $result = get_stmt_result($connection, $sql, 'i', [$id]);

    return mysqli_fetch_assoc($result);
}

/**
 * @param mysqli $connection
 * @param array $data
 *
 * @return int
 */
function add_lot(mysqli $connection, array $data): int
{
    $sql = <<<SQL
        INSERT INTO lots (
            `author_id`,
            `category_id`,
            `title`,
            `description`,
            `image_url`,
            `start_price`,
            `bet_step`,
            `expire_date`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    SQL;

    $stmt = execute_stmt($connection, $sql, 'iisssiis', $data);

    if (mysqli_stmt_affected_rows($stmt) !== 1) {
        exit('Ошибка добавления лота');
    }

    return mysqli_insert_id($connection);
}
