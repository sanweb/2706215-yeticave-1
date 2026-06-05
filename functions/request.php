<?php

declare(strict_types=1);

function get_request_uri(): string
{
    return $_SERVER['REQUEST_URI'] ?? '';
}

function get_request_method(): string
{
    return $_SERVER['REQUEST_METHOD'] ?? '';
}

function is_get_request(): bool
{
    return get_request_method() === HttpMethodEnum::GET->value;
}

function is_post_request(): bool
{
    return get_request_method() === HttpMethodEnum::POST->value;
}

function get_query_param(string $key, mixed $default = null): mixed
{
    $value = $_GET[$key] ?? $default;

    return is_string($value) ? trim($value) : $value;
}

function get_post_param(string $key, mixed $default = null): mixed
{
    $value = $_POST[$key] ?? $default;

    return is_string($value) ? trim($value) : $value;
}
