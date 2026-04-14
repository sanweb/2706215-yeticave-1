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

    $formattedPrice = $price < 1000 ? $price : number_format($price, 0, ',', ' ');

    return $formattedPrice . ' <b class="rub">р</b>';
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
 * @param string $date Expiration date in Y-m-d format.
 *
 * @return array Remaining hours and minutes.
 */
function get_dt_range(string $date): array
{
    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return [0, 0];
    }

    $secondsLeft = $timestamp - time();

    if ($secondsLeft > 0) {
        $hours = (int) ($secondsLeft / 3600);
        $minutes = (int) (($secondsLeft % 3600) / 60);

        return [$hours, $minutes];
    }

    return [0, 0];
}
