<?php

declare(strict_types=1);

/**
 * Formats a price and adds the ruble sign.
 *
 * @param int $price Price value.
 *
 * @return string Formatted price with the ruble sign.
 */
function format_price(int $price): string
{
    // ceil() is kept to follow the specification, although it has no practical effect here because $price is already typed as int
    $price = ceil($price);

    $formatted_price = $price < 1000 ? $price : number_format($price, 0, ',', ' ');

    return $formatted_price . ' <b class="rub">р</b>';
}

/**
 * Escapes a string for safe HTML output.
 *
 * @param string $value Raw string value.
 *
 * @return string Escaped string.
 */
function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Returns the time remaining until the specified date.
 *
 * Returns zero time if the date is invalid or already expired.
 *
 * @param string $date Expiration date in Y-m-d format.
 *
 * @return array{0: int, 1: int} Remaining hours and minutes.
 */
function get_dt_range(string $date): array
{
    $timestamp = strtotime($date);

    if ($timestamp !== false) {
        $seconds_left = $timestamp - time();

        if ($seconds_left > 0) {
            $hours = (int) ($seconds_left / SECONDS_PER_HOUR);
            $minutes = (int) (($seconds_left % SECONDS_PER_HOUR) / SECONDS_PER_MINUTE);

            return [$hours, $minutes];
        }
    }

    return [0, 0];
}

/**
 * Formats remaining time as HH:MM.
 *
 * @param array{0: int, 1: int} $time_left Remaining hours and minutes.
 *
 * @return string Formatted remaining time.
 */
function format_time_left(array $time_left): string
{
    $hours = str_pad((string) $time_left[0], 2, '0', STR_PAD_LEFT);
    $minutes = str_pad((string) $time_left[1], 2, '0', STR_PAD_LEFT);

    return $hours . ':' . $minutes;
}
