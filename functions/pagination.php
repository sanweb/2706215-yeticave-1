<?php

declare(strict_types=1);

/**
 * Builds pagination URLs for a list page.
 *
 * Returns previous, next, and numbered page URLs based on the given base URL,
 * URL parameters, total items count, items per page, and current page.
 *
 * @param string $base_url Base page URL, for example: /search.php.
 * @param array<string, string|int|float|bool|null> $url_params Query parameters to include in pagination URLs.
 * @param int $total_items Total number of items.
 * @param int $items_per_page Number of items per page.
 * @param int $current_page Current page number.
 *
 * @return array{
 *     prev: string,
 *     next: string,
 *     pages: array<int, string>
 * } Pagination URLs.
 */
function build_pagination(string $base_url, array $url_params, int $total_items, int $items_per_page, int $current_page = 1): array
{
    $current_page = (int) max(1, $current_page);
    $items_per_page = (int) max(1, $items_per_page);
    $pages_count = (int) ceil($total_items / $items_per_page);

    $pagination = [
        'prev'  => '',
        'next'  => '',
        'pages' => [],
    ];

    if ($pages_count > 1) {
        for ($i = 1; $i <= $pages_count; $i++) {
            $url_params['page'] = $i;
            $pagination['pages'][$i] = $base_url . '?' . http_build_query($url_params);
        }

        if ($current_page > 1) {
            $url_params['page'] = $current_page - 1;
            $pagination['prev'] = $base_url . '?' . http_build_query($url_params);
        }

        if ($current_page < $pages_count) {
            $url_params['page'] = $current_page + 1;
            $pagination['next'] = $base_url . '?' . http_build_query($url_params);
        }
    }

    return $pagination;
}
