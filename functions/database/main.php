<?php

declare(strict_types=1);

// TODO: Replace exit() calls with exceptions and show errors on the error.php page.

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
            lots.`author_id`,
            categories.`name` AS `category_name`,
            (
                SELECT `user_id`
                FROM bets b1
                WHERE b1.`lot_id` = lots.`id`
                ORDER BY b1.`amount`
                DESC LIMIT 1
            ) AS max_bet_user_id
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
function create_lot(mysqli $connection, array $data): int
{
    $sql = <<<SQL
        INSERT INTO `lots` (
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

/**
 * @param mysqli $connection
 * @param array $data
 *
 * @return int
 */
function create_bet(mysqli $connection, array $data): int
{
    $sql = <<<SQL
        INSERT INTO `bets` (
            `user_id`,
            `lot_id`,
            `amount`
        ) VALUES (?, ?, ?)
    SQL;

    $stmt = execute_stmt($connection, $sql, 'iii', $data);

    if (mysqli_stmt_affected_rows($stmt) !== 1) {
        exit('Ошибка добавления ставки');
    }

    return mysqli_insert_id($connection);
}

/**
 * @param mysqli $connection
 * @param array $data
 *
 * @return int
 */
function create_user(mysqli $connection, array $data): int
{
    $sql = <<<SQL
        INSERT INTO `users` (
            `email`,
            `name`,
            `password_hash`,
            `contact_info`
        ) VALUES (?, ?, ?, ?)
    SQL;

    $stmt = execute_stmt($connection, $sql, 'ssss', $data);

    if (mysqli_stmt_affected_rows($stmt) !== 1) {
        exit('Ошибка создания аккаунта');
    }

    return mysqli_insert_id($connection);
}

/**
 * @param mysqli $connection
 * @param string $email
 *
 * @return array|null
 */
function get_user_by_email(mysqli $connection, string $email): ?array
{
    $sql = <<<SQL
        SELECT
            `id`,
            `email`,
            `name`,
            `password_hash`,
            `contact_info`,
            `created_at`,
            `updated_at`
        FROM `users`
        WHERE users.`email` = ?
    SQL;

    $result = get_stmt_result($connection, $sql, 's', [$email]);

    return mysqli_fetch_assoc($result);
}

/**
 * Returns the total number of lots matching the search phrase.
 *
 * @param mysqli $connection
 * @param string $search_phrase
 * @return int Total number of matching lots.
 */
function get_total_lots_by_phrase(mysqli $connection, string $search_phrase): int
{
    $sql = <<<SQL
        SELECT COUNT(*) AS `total`
        FROM `lots`
        WHERE MATCH(`title`, `description`) AGAINST (?)
    SQL;

    $result = get_stmt_result($connection, $sql, 's', [$search_phrase]);

    return mysqli_fetch_column($result);
}

/**
 * Returns lots matching the search phrase for the specified page.
 *
 * @param mysqli $connection
 * @param string $search_phrase
 * @param int $lots_per_page
 * @param int $page
 * @return array<int, array<string, mixed>> Matching lots list.
 */
function get_lots_by_phrase(mysqli $connection, string $search_phrase, int $lots_per_page, int $page = 1): array
{
    $page = (int) max(1, $page);
    $lots_per_page = (int) max(1, $lots_per_page);
    $offset = (int) ($page - 1) * $lots_per_page;

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
        WHERE MATCH(`title`, `description`) AGAINST (?)
        LIMIT ?
        OFFSET ?
    SQL;

    $result = get_stmt_result($connection, $sql, 'sii', [$search_phrase, $lots_per_page, $offset]);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
