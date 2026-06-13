<?php

declare(strict_types=1);

/**
 * Checks whether the current request method is POST.
 *
 * @return bool True if the current request method is POST.
 */
function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === HttpMethodEnum::POST->value;
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
