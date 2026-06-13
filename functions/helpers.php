<?php

declare(strict_types=1);

/**
 * Returns the correct Russian noun form for a given integer number.
 *
 * Works with integer values only.
 *
 * Example:
 * $remaining_minutes = 5;
 *
 * echo "Я поставил таймер на {$remaining_minutes} "
 *     . get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 *
 * Result: "Я поставил таймер на 5 минут"
 *
 * @param int $number Number used to choose the correct noun form.
 * @param string $one Singular form, for example: минута, час, яблоко.
 * @param string $two Plural form used with 2, 3, 4, for example: минуты, часа, яблока.
 * @param string $many Plural form used with 0, 5-20 and other values, for example: минут, часов, яблок.
 *
 * @return string Correct noun form for the given number.
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Renders a template with the given data and returns the resulting HTML.
 *
 * @param string $name Template file name or path relative to the templates directory.
 * @param array $data Associative array of data that will be available in the template as variables.
 *
 * @return string Resulting HTML content.
 */
function include_template(string $name, array $data = []): string
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
