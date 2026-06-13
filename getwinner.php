<?php

declare(strict_types=1);

/** @var mysqli $db_connection */

require_once BASE_PATH . '/functions/mailer.php';

$smtp_config = require BASE_PATH . '/config/smtp.php';
$dsn = build_dsn($smtp_config);

$lot_winner_candidates = get_lot_winner_candidates($db_connection);

if (!empty($lot_winner_candidates)) {
    foreach ($lot_winner_candidates as $winner_candidate) {
        $lot_id = (int) ($winner_candidate['lot_id'] ?? 0);
        $winner_bet_id = (int) ($winner_candidate['bet_id'] ?? 0);

        if (
            $lot_id > 0
            && $winner_bet_id > 0
            && assign_lot_winner_bet_id($db_connection, $lot_id, $winner_bet_id)
        ) {
            // Send email to winner
            $user_name = $winner_candidate['user_name'] ?? '';
            $user_email = $winner_candidate['user_email'] ?? '';

            if (!empty($user_name) && !empty($user_email)) {
                $from = 'keks@phpdemo.ru';
                $to = sprintf('%s <%s>', $user_name, $user_email);
                $subject = 'Ваша ставка победила';
                $body = include_template('email.php', [
                    'user_name'   => $user_name,
                    'lot_title'   => $winner_candidate['lot_title'] ?? '',
                    'lot_url'     => BASE_URL . '/lot.php?id=' . $lot_id,
                    'my_bets_url' => BASE_URL . '/my-bets.php',
                ]);
                $content_type = 'text/html';
                send_mail($dsn, $to, $from, $subject, $body, $content_type);
            }
        }
    }
}
