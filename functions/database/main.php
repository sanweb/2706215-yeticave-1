<?php

declare(strict_types=1);

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
 * Returns a lot by ID with category name, current price and bet information.
 *
 * The current price is the highest bet amount or the start price if there are no bets.
 * The minimum bet is calculated as the current price plus the bet step.
 * The expiration date is returned in YYYY-MM-DD format.
 *
 * @param mysqli $connection MySQL database connection.
 * @param int $id Lot ID.
 *
 * @return array{
 *     id: string,
 *     title: string,
 *     description: string,
 *     image_url: string,
 *     start_price: string,
 *     price: string,
 *     bet_step: string,
 *     min_bet: string,
 *     expire_date: string,
 *     is_expired: string,
 *     has_winner: string,
 *     author_id: string,
 *     category_name: string,
 *     max_bet_user_id: string|null
 * }|null
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
            IF(lots.`expire_date` <= CURRENT_DATE, 1, 0) AS `is_expired`,
            IF(lots.`winner_bet_id` IS NOT NULL, 1, 0) AS `has_winner`,
            lots.`author_id`,
            categories.`name` AS `category_name`,
            (
                SELECT b1.`user_id`
                FROM bets AS b1
                WHERE b1.`lot_id` = lots.`id`
                ORDER BY b1.`amount` DESC
                LIMIT 1
            ) AS max_bet_user_id
        FROM `lots`
            JOIN `categories` ON lots.`category_id` = categories.`id`
            LEFT JOIN `bets` ON bets.`lot_id` = lots.`id`
        WHERE lots.`id` = ?
        GROUP BY lots.`id`
    SQL;

    $result = get_stmt_result($connection, $sql, 'i', [$id]);

    return mysqli_fetch_assoc($result) ?: null;
}

/**
 * Creates a new lot.
 *
 * The data array must contain values in the same order as the SQL placeholders:
 * author ID, category ID, title, description, image URL, start price, bet step, expire date.
 *
 * @param mysqli $connection MySQL database connection.
 * @param array $data Lot data.
 *
 * @return int Created lot ID.
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
 * Creates a new bet.
 *
 * The data array must contain values in the same order as the SQL placeholders:
 * user ID, lot ID, amount.
 *
 * @param mysqli $connection MySQL database connection.
 * @param array $data Bet data.
 *
 * @return int Created bet ID.
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
 * Creates a new user account.
 *
 * The data array must contain values in the same order as the SQL placeholders:
 * email, name, password hash, contact info.
 *
 * @param mysqli $connection MySQL database connection.
 * @param array $data User data.
 *
 * @return int Created user ID.
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
 * Returns a user by email.
 *
 * @param mysqli $connection MySQL database connection.
 * @param string $email User email.
 *
 * @return array{
 *     id: string,
 *     email: string,
 *     name: string,
 *     password_hash: string,
 *     contact_info: string|null,
 *     created_at: string,
 *     updated_at: string|null
 * }|null
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

    return mysqli_fetch_assoc($result) ?: null;
}

/**
 * Returns the total number of active lots matching the search phrase.
 *
 * @param mysqli $connection MySQL database connection.
 * @param string $search_phrase Search phrase.
 *
 * @return int Total number of matching active lots.
 */
function get_total_lots_by_phrase(mysqli $connection, string $search_phrase): int
{
    $sql = <<<SQL
        SELECT COUNT(*) AS `total`
        FROM `lots`
        WHERE MATCH(`title`, `description`) AGAINST (?) AND `expire_date` > CURRENT_DATE
    SQL;

    $result = get_stmt_result($connection, $sql, 's', [$search_phrase]);

    return (int) mysqli_fetch_column($result);
}

/**
 * Returns active lots matching the search phrase for the specified page.
 *
 * The current price is the highest bet amount or the start price if there are no bets.
 * The expiration date is returned in YYYY-MM-DD format.
 *
 * @param mysqli $connection MySQL database connection.
 * @param string $search_phrase Search phrase.
 * @param int $page Current page number.
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
function get_lots_by_phrase(mysqli $connection, string $search_phrase, int $page = 1): array
{
    $page = (int) max(1, $page);
    $lots_per_page = LOTS_PER_PAGE;
    $offset = ($page - 1) * $lots_per_page;

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
        WHERE MATCH(`title`, `description`) AGAINST (?) AND `expire_date` > CURRENT_DATE
        ORDER BY lots.`created_at` DESC
        LIMIT ?
        OFFSET ?
    SQL;

    $result = get_stmt_result($connection, $sql, 'sii', [$search_phrase, $lots_per_page, $offset]);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Returns the total number of active lots from the specified category.
 *
 * @param mysqli $connection MySQL database connection.
 * @param int $category_id Category ID.
 *
 * @return int Total number of matching active lots.
 */
function get_total_lots_by_category_id(mysqli $connection, int $category_id): int
{
    $sql = <<<SQL
        SELECT COUNT(*) AS `total`
        FROM `lots`
        WHERE `category_id` = ? AND `expire_date` > CURRENT_DATE
    SQL;

    $result = get_stmt_result($connection, $sql, 'i', [$category_id]);

    return (int) mysqli_fetch_column($result);
}

/**
 * Returns active lots from the specified category for the specified page.
 *
 * The current price is the highest bet amount or the start price if there are no bets.
 * The expiration date is returned in YYYY-MM-DD format.
 *
 * @param mysqli $connection MySQL database connection.
 * @param int $category_id Category ID.
 * @param int $lots_per_page Number of lots per page.
 * @param int $page Current page number.
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
function get_lots_by_category_id(mysqli $connection, int $category_id, int $lots_per_page, int $page = 1): array
{
    $page = (int) max(1, $page);
    $lots_per_page = (int) max(1, $lots_per_page);
    $offset = ($page - 1) * $lots_per_page;

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
        WHERE `category_id` = ? AND `expire_date` > CURRENT_DATE
        ORDER BY lots.`created_at` DESC
        LIMIT ?
        OFFSET ?
    SQL;

    $result = get_stmt_result($connection, $sql, 'iii', [$category_id, $lots_per_page, $offset]);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Returns the user's latest bet for each lot.
 *
 * For each lot, only the latest bet made by the user is returned.
 * If the user's bet is the winning bet, the lot author's contact info is returned.
 * Otherwise, contact_info is an empty string.
 *
 * @param mysqli $connection MySQL database connection.
 * @param int $user_id User ID.
 *
 * @return array<int, array{
 *     lot_id: string,
 *     title: string,
 *     image_url: string,
 *     expire_date: string,
 *     is_expired: string,
 *     is_win: string,
 *     contact_info: string,
 *     category_name: string,
 *     bet_amount: string,
 *     bet_created_at: string
 * }>
 */
function get_bets_by_user_id(mysqli $connection, int $user_id): array
{
    $sql = <<<SQL
        SELECT
            lots.`id` AS `lot_id`,
            lots.`title`,
            lots.`image_url`,
            DATE_FORMAT(lots.`expire_date`, '%Y-%m-%d') AS `expire_date`,
            IF(lots.`expire_date` <= CURRENT_DATE, 1, 0) AS `is_expired`,
            IF(lots.`winner_bet_id` = user_bets.`id`, 1, 0) AS `is_win`,
            IF(lots.`winner_bet_id` = user_bets.`id`, lot_authors.`contact_info`, '') AS `contact_info`,
            categories.`name` AS `category_name`,
            user_bets.`amount` AS `bet_amount`,
            DATE_FORMAT(user_bets.`created_at`, '%Y-%m-%d %H:%i:%s') AS `bet_created_at`
        FROM
            (
                SELECT bets.*
                FROM `bets`
                    JOIN (
                        SELECT `lot_id`, MAX(`id`) AS `id`
                        FROM bets
                        WHERE `user_id` = ?
                        GROUP BY `lot_id`
                    ) AS last_user_bets ON bets.`id` = last_user_bets.`id`
            ) AS user_bets
            JOIN `lots` ON lots.`id` = user_bets.`lot_id`
            JOIN `categories` ON lots.`category_id` = categories.`id`
            JOIN `users` AS `lot_authors` ON lots.`author_id` = lot_authors.`id`
        ORDER BY user_bets.`created_at` DESC
    SQL;

    $result = get_stmt_result($connection, $sql, 'i', [$user_id]);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Returns bet history for the specified lot.
 *
 * Bets are sorted from newest to oldest.
 * The creation date is returned in YYYY-MM-DD HH:MM:SS format.
 *
 * @param mysqli $connection MySQL database connection.
 * @param int $lot_id Lot ID.
 *
 * @return array<int, array{
 *     user_name: string,
 *     amount: string,
 *     created_at: string
 * }>
 */
function get_bet_history_by_lot_id(mysqli $connection, int $lot_id): array
{
    $sql = <<<SQL
        SELECT
            users.`name` AS `user_name`,
            bets.`amount`,
            DATE_FORMAT(bets.`created_at`, '%Y-%m-%d %H:%i:%s') AS `created_at`
        FROM bets
            JOIN `users` ON bets.`user_id` = users.`id`
        WHERE `lot_id` = ?
        ORDER BY bets.`created_at` DESC
    SQL;

    $result = get_stmt_result($connection, $sql, 'i', [$lot_id]);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Returns winner candidates for expired lots without an assigned winner.
 *
 * Each candidate is based on the latest bet for the expired lot.
 * According to the project rules, each new bet is greater than the previous one,
 * so the latest bet is also the winning bet.
 *
 * @param mysqli $connection MySQL database connection.
 *
 * @return array<int, array{
 *     id: string,
 *     title: string,
 *     bet_id: string,
 *     user_id: string,
 *     email: string,
 *     name: string
 * }>
 */
function get_lot_winner_candidates(mysqli $connection): array
{
    $sql = <<<SQL
        SELECT
            lots.`id` AS `lot_id`,
            lots.`title` AS `lot_title`,
            bets.`id` AS `bet_id`,
            bets.`user_id`,
            users.`email` AS `user_email`,
            users.`name` AS `user_name`
        FROM `lots`
            JOIN (
                SELECT `lot_id`, MAX(`id`) AS `id`
                FROM `bets`
                GROUP BY `lot_id`
            ) AS max_bets ON max_bets.`lot_id` = lots.`id`
            JOIN `bets` ON bets.`id` = max_bets.`id`
            JOIN `users` ON users.`id` = bets.`user_id`
            WHERE lots.`expire_date` <= CURRENT_DATE
                AND lots.`winner_bet_id` IS NULL
    SQL;

    $result = get_query_result($connection, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Assigns the winning bet to a lot.
 *
 * The winner is assigned only if the lot does not already have a winner.
 *
 * @param mysqli $connection MySQL database connection.
 * @param int $lot_id Lot ID.
 * @param int $bet_id Winning bet ID.
 *
 * @return int Number of affected rows.
 */
function assign_lot_winner_bet_id(mysqli $connection, int $lot_id, int $bet_id): int
{
    $sql = <<<SQL
        UPDATE `lots`
        SET `winner_bet_id` = ?
        WHERE `id` = ? AND `winner_bet_id` IS NULL
    SQL;

    $stmt = execute_stmt($connection, $sql, 'ii', [$bet_id, $lot_id]);

    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if ($affected_rows !== 1) {
        exit('Ошибка назначения победителя лота');
    }

    return $affected_rows;
}
