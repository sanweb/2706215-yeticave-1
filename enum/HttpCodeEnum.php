<?php

declare(strict_types=1);

/**
 * HTTP response status codes used by the application.
 */
enum HttpCodeEnum: int
{
    case OK = 200;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
}
