<?php

declare(strict_types=1);

// DateTime
const SECONDS_PER_MINUTE = 60;
const SECONDS_PER_HOUR   = SECONDS_PER_MINUTE * 60;

const DATE_FORMAT = 'Y-m-d';
const DATETIME_FORMAT = 'Y-m-d H:i:s';

// Assets
const ASSET_TYPE_CSS = 'css';
const ASSET_TYPE_JS  = 'js';

/**
 * Formats a price and optionally adds a currency sign suffix.
 *
 * The suffix is appended as-is and may include HTML and/or leading spaces
 * if a visual separator between the price and the currency sign is needed.
 *
 * @param int $price Price value.
 * @param bool $show_currency Whether to append the currency sign suffix.
 * @param string $currency_sign_suffix Currency sign suffix appended to the formatted price.
 *
 * @return string Formatted price, optionally with the currency sign suffix.
 */
function format_price(int $price, bool $show_currency = true, string $currency_sign_suffix = '<b class="rub">р</b>'): string
{
    $formatted_price = number_format($price, 0, ',', ' ');

    if ($show_currency) {
        $formatted_price .= $currency_sign_suffix;
    }

    return $formatted_price;
}

/**
 * Returns the time left until the end of the given date.
 *
 * The date is treated as a calendar date in the application timezone.
 * The lot expires at 23:59:59 on this date.
 *
 * Expected date format: Y-m-d.
 *
 * Invalid or expired dates return [0, 0].
 *
 * @param string $date Expiration date in YYYY-MM-DD format.
 *
 * @return array{0: int, 1: int} Time left as [hours, minutes].
 */
function get_time_left(string $date): array
{
    $hours_left = 0;
    $minutes_left = 0;

    if (is_datetime_valid($date, DATE_FORMAT)) {
        $expiration_date = date_create_immutable_from_format('!Y-m-d H:i:s', "{$date} 23:59:59");
        $now = date_create_immutable();

        if ($expiration_date !== false) {
            $seconds_left = max(0, date_timestamp_get($expiration_date) - date_timestamp_get($now));

            $hours_left = intdiv($seconds_left, SECONDS_PER_HOUR);
            $minutes_left = intdiv($seconds_left % SECONDS_PER_HOUR, SECONDS_PER_MINUTE);
        }
    }

    return [$hours_left, $minutes_left];
}

/**
 * Formats remaining time as HH:MM.
 *
 * @param array{0: int, 1: int} $time_left Remaining time as [hours, minutes].
 *
 * @return string Formatted remaining time.
 */
function format_time_left(array $time_left): string
{
    return sprintf('%02d:%02d', $time_left[0], $time_left[1]);
}

/**
 * Returns elapsed time from the given datetime to the current moment.
 *
 * If the datetime is invalid, returns null as datetime and zero values
 * for hours and minutes.
 *
 * @param string $datetime Datetime string in DATETIME_FORMAT format.
 *
 * @return array{
 *     datetime: DateTimeImmutable|null,
 *     hours: int,
 *     minutes: int
 * }
 */
function get_elapsed_time(string $datetime): array
{
    $past_date = null;
    $hours = 0;
    $minutes = 0;

    if (is_datetime_valid($datetime, DATETIME_FORMAT)) {
        $past_date = date_create_immutable_from_format(DATETIME_FORMAT, $datetime);
        $now = date_create_immutable();

        if ($past_date !== false) {
            $seconds_elapsed = max(0, date_timestamp_get($now) - date_timestamp_get($past_date));

            $hours = intdiv($seconds_elapsed, SECONDS_PER_HOUR);
            $minutes = intdiv($seconds_elapsed % SECONDS_PER_HOUR, SECONDS_PER_MINUTE);
        }
    }

    return [
        'datetime' => $past_date,
        'hours' => $hours,
        'minutes' => $minutes,
    ];
}

/**
 * Formats how much time has passed since a bet was created.
 *
 * Returns a relative value for today's bets, "Вчера в H:i" for yesterday's bets,
 * or a full date and time for older bets.
 *
 * @param string $datetime Bet creation datetime in DATETIME_FORMAT format.
 *
 * @return string Formatted time since bet, or an empty string if datetime is invalid.
 */
function format_time_since_bet(string $datetime): string
{
    $elapsed_time = get_elapsed_time($datetime);
    $bet_created_datetime = $elapsed_time['datetime'] ?? null;

    if ($bet_created_datetime === null || $bet_created_datetime === false) {
        $time_since_bet = '';
    } elseif (is_today($bet_created_datetime)) {
        $hours_elapsed = $elapsed_time['hours'] ?? 0;
        $minutes_elapsed = $elapsed_time['minutes'] ?? 0;

        if ($hours_elapsed === 0 && $minutes_elapsed === 0) {
            $time_since_bet = 'Меньше минуты назад';
        } elseif ($hours_elapsed === 0 && $minutes_elapsed > 0) {
            $time_since_bet = $minutes_elapsed . ' '
                . get_noun_plural_form(
                    $minutes_elapsed,
                    'минута',
                    'минуты',
                    'минут'
                ) . ' назад';
        } else {
            $time_since_bet = $hours_elapsed . ' '
                . get_noun_plural_form(
                    $hours_elapsed,
                    'час',
                    'часы',
                    'часов'
                ) . ' назад';
        }
    } elseif (is_yesterday($bet_created_datetime)) {
        $time_since_bet = 'Вчера в ' . date_format($bet_created_datetime, 'H:i');
    } else {
        $time_since_bet = date_format($bet_created_datetime, 'd.m.Y')
            . ' в ' . date_format($bet_created_datetime, 'H:i');
    }

    return $time_since_bet;
}

/**
 * Checks whether the given datetime is today.
 *
 * @param DateTimeImmutable $datetime Datetime to check.
 *
 * @return bool True if the datetime is today.
 */
function is_today(DateTimeImmutable $datetime): bool
{
    $today = date_create_immutable('today');

    return date_format($datetime, DATE_FORMAT) === date_format($today, DATE_FORMAT);
}

/**
 * Checks whether the given datetime is yesterday.
 *
 * @param DateTimeImmutable $datetime Datetime to check.
 *
 * @return bool True if the datetime is yesterday.
 */
function is_yesterday(DateTimeImmutable $datetime): bool
{
    $yesterday = date_create_immutable('yesterday');

    return date_format($datetime, DATE_FORMAT) === date_format($yesterday, DATE_FORMAT);
}

/**
 * Checks whether a date or datetime string strictly matches the given format.
 *
 * @param string $value Date or datetime string.
 * @param string $format Expected date format.
 *
 * @return bool True if the value is valid and strictly matches the format.
 */
function is_datetime_valid(string $value, string $format = DATE_FORMAT): bool
{
    $datetime_obj = date_create_immutable_from_format($format, $value);
    $errors = date_get_last_errors();

    if ($errors === false) {
        $errors = [
            'warning_count' => 0,
            'error_count' => 0,
        ];
    }

    return $datetime_obj !== false
        && date_format($datetime_obj, $format) === $value
        && $errors['warning_count'] === 0
        && $errors['error_count'] === 0;
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
 * Generates HTML tags for asset files.
 *
 * @param string $type Asset type.
 * @param string[] $files List of asset file paths.
 *
 * @return string HTML tags separated by line breaks.
 */
function include_asset_files(string $type, array $files = []): string
{
    $tags = [];

    foreach ($files as $file) {
        $file = esc($file);

        if ($type === ASSET_TYPE_CSS) {
            $tags[] = '<link href="' . $file . '" rel="stylesheet">';
        } elseif ($type === ASSET_TYPE_JS) {
            $tags[] = '<script src="' . $file . '"></script>';
        }
    }

    return implode(PHP_EOL, $tags);
}

/**
 * Checks whether the current page is the home page.
 *
 * @return bool
 */
function is_home_page(): bool
{
    $path = parse_url(get_request_uri(), PHP_URL_PATH);

    return in_array($path, ['/', '/index.php'], true);
}

/**
 * Redirects to the given URL and stops script execution.
 *
 * @param string $url Redirect URL.
 *
 * @return never
 */
function redirect_to(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Renders the 404 error page and sends the HTTP 404 status code.
 *
 * @param array $categories List of categories used in the layout and 404 page.
 * @param array|null $user Current authenticated user data, or null for guest.
 *
 * @return void
 */
function render_page_404(array $categories, ?array $user): void
{
    http_response_code(HttpCodeEnum::NOT_FOUND->value);

    $main_content = include_template('404.php', compact('categories'));

    $page_content = include_template('layout/main.php', [
        'page_title'     => '404 Страница не найдена',
        'user'           => $user,
        'categories'     => $categories,
        'main_content'   => $main_content,
        'main_classname' => '',
    ]);

    echo $page_content;
}

/**
 * Returns a price label depending on the lot state.
 *
 * @param array $lot Lot data.
 *
 * @return string Price label.
 */
function get_lot_price_label(array $lot): string
{
    if (!empty($lot['has_winner'])) {
        $price_label = 'Цена победителя';
    } elseif (!empty($lot['is_expired'])) {
        $price_label = 'Итоговая цена';
    } else {
        $price_label = 'Текущая цена';
    }

    return $price_label;
}
