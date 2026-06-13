<?php

declare(strict_types=1);

/**
 * Returns the current request URI.
 *
 * The URI usually contains the path and query string, for example:
 * /lots.php?id=10.
 *
 * @return string Current request URI or an empty string if it is not available.
 */
function get_request_uri(): string
{
    return $_SERVER['REQUEST_URI'] ?? '';
}

/**
 * Returns the current HTTP request method.
 *
 * For example: GET, POST.
 *
 * @return string Current request method or an empty string if it is not available.
 */
function get_request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? '');
}

/**
 * Checks whether the current request method is POST.
 *
 * @return bool True if the current request method is POST.
 */
function is_post_request(): bool
{
    return get_request_method() === HttpMethodEnum::POST->value;
}

/**
 * Returns a query parameter value by key.
 *
 * If the parameter exists and its value is a string, the value will be trimmed.
 * If the parameter does not exist, the default value will be returned.
 *
 * @param string $key Query parameter name.
 * @param mixed $default Default value returned when the parameter is missing.
 *
 * @return mixed Query parameter value or the default value.
 */
function get_query_param(string $key, mixed $default = null): mixed
{
    $value = $_GET[$key] ?? $default;

    return is_string($value) ? trim($value) : $value;
}
