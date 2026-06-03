<?php

declare(strict_types=1);

function build_pagination(string $base_url, array $url_params, int $total_items, int $page = 1, int $items_per_page = LOTS_PER_PAGE): array
{
    $page = (int) max(1, $page);
    $items_per_page = (int) max(1, $items_per_page);
    $pages_count = (int) ceil($total_items / $items_per_page);

    $pagination = [
        'prev' => '',
        'next' => '',
        'pages' => [],
    ];

    if ($pages_count > 1) {
        for ($i = 1; $i <= $pages_count; $i++) {
            $url_params['page'] = $i;
            $pagination['pages'][$i] = $base_url . '?' . http_build_query($url_params);
        }

        if ($page > 1) {
            $url_params['page'] = $page - 1;
            $pagination['prev'] = $base_url . '?' . http_build_query($url_params);
        }

        if ($page < $pages_count) {
            $url_params['page'] = $page + 1;
            $pagination['next'] = $base_url . '?' . http_build_query($url_params);
        }
    }

    return $pagination;
}
