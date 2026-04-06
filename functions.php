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
