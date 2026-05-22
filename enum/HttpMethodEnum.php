<?php

declare(strict_types=1);

/**
 * HTTP methods supported by the application.
 */
enum HttpMethodEnum: string
{
    case GET = 'GET';
    case POST = 'POST';
}