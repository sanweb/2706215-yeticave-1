<?php

declare(strict_types=1);

/** @var mysqli $db_connection */

$expired_lots_winner_candidates = get_expired_lots_winner_candidates($db_connection);

if (!empty($expired_lots_winner_candidates)) {
    foreach ($expired_lots_winner_candidates as $winner_candidate) {
        $lot_id = (int) ($winner_candidate['lot_id'] ?? 0);
        $winner_bet_id = (int) ($winner_candidate['bet_id'] ?? 0);

        if (
            $lot_id > 0
            && $winner_bet_id > 0
            && assign_lot_winner_bet_id($db_connection, [$lot_id, $winner_bet_id])
        ) {
            // Send email
        }
    }
}
